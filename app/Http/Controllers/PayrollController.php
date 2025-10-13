<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Deduction;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        
        // Auto-generate payrolls if they don't exist
        $this->autoGeneratePayrolls($month);
        
        $payrolls = Payroll::where('period', $month)
                          ->with(['employee.attendances' => function($q) use ($month) {
                              $q->whereYear('date', Carbon::parse($month)->year)
                                ->whereMonth('date', Carbon::parse($month)->month);
                          }, 'deductions'])
                          ->get()
                          ->map(function($payroll) use ($month) {
                              $payroll->total_deductions = $payroll->deductions->sum('amount');
                              
                              // For pending payrolls, calculate real-time values
                              if ($payroll->status === 'pending') {
                                  $currentHours = $this->calculateTotalHours($payroll->employee->attendances);
                                  $currentRate = $payroll->employee->hourly_rate ?? $payroll->hourly_rate ?? 100;
                                  $currentGrossPay = $currentHours * $currentRate;
                                  $hasIncompleteAttendance = $this->hasIncompleteAttendance($payroll->employee->attendances);
                                  
                                  // Override with live calculations
                                  $payroll->total_hours = $currentHours;
                                  $payroll->gross_pay = $currentGrossPay;
                                  $payroll->has_incomplete_attendance = $hasIncompleteAttendance;
                              }
                              
                              return $payroll;
                          });

        return view('employee.payroll', compact('payrolls', 'month'));
    }

    private function autoGeneratePayrolls($month)
    {
        $employees = Employee::active()->with(['attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        foreach ($employees as $employee) {
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                                    ->where('period', $month)
                                    ->first();
            
            if (!$existingPayroll) {
                $totalHours = $this->calculateTotalHours($employee->attendances);
                $hourlyRate = $employee->hourly_rate ?? 100;
                $grossPay = $totalHours * $hourlyRate;

                Payroll::create([
                    'employee_id' => $employee->id,
                    'period' => $month,
                    'total_hours' => $totalHours,
                    'hourly_rate' => $hourlyRate,
                    'gross_pay' => $grossPay,
                    'status' => 'pending'
                ]);
            } elseif ($existingPayroll->status === 'pending' && $employee->hourly_rate && $existingPayroll->hourly_rate != $employee->hourly_rate) {
                // Update pending payroll with current employee rate
                $totalHours = $this->calculateTotalHours($employee->attendances);
                $hourlyRate = $employee->hourly_rate;
                $grossPay = $totalHours * $hourlyRate;
                
                $existingPayroll->update([
                    'total_hours' => $totalHours,
                    'hourly_rate' => $hourlyRate,
                    'gross_pay' => $grossPay
                ]);
            }
        }
    }

    public function generate(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $autoPay = $request->boolean('auto_pay', false);

        $employees = Employee::active()->with(['attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        $updated = 0;

        foreach ($employees as $employee) {
            $totalHours = $this->calculateTotalHours($employee->attendances);
            $hourlyRate = $employee->hourly_rate ?? 100;
            $grossPay = $totalHours * $hourlyRate;

            $payroll = Payroll::where('employee_id', $employee->id)
                             ->where('period', $month)
                             ->first();

            if ($payroll) {
                if ($payroll->status === 'pending') {
                    $payroll->update([
                        'total_hours' => $totalHours,
                        'hourly_rate' => $hourlyRate,
                        'gross_pay' => $grossPay,
                        'status' => $autoPay ? 'paid' : 'pending',
                        'pay_date' => $autoPay ? now() : null
                    ]);
                    $updated++;
                }
            } else {
                Payroll::create([
                    'employee_id' => $employee->id,
                    'period' => $month,
                    'total_hours' => $totalHours,
                    'hourly_rate' => $hourlyRate,
                    'gross_pay' => $grossPay,
                    'status' => $autoPay ? 'paid' : 'pending',
                    'pay_date' => $autoPay ? now() : null
                ]);
                $updated++;
            }
        }

        $message = $autoPay ? "Auto-paid {$updated} payrolls!" : "Updated {$updated} payroll records!";

        return redirect()->route('employee.payroll', ['month' => $month])
                        ->with('success', $message);
    }

    public function markPaid($id)
    {
        $payroll = Payroll::with(['employee.attendances' => function($q) {
            $month = request('month', now()->format('Y-m'));
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->findOrFail($id);
        
        // Check for incomplete attendance
        if ($this->hasIncompleteAttendance($payroll->employee->attendances)) {
            return redirect()->back()->with('error', 'Cannot pay employee with incomplete attendance records (missing time out).');
        }
        
        // Auto time-out any ongoing shifts before payment
        $this->autoTimeOutOngoingShifts($payroll->employee->attendances);
        
        // Calculate final values before marking as paid
        $currentHours = $this->calculateTotalHours($payroll->employee->attendances);
        $currentRate = $payroll->employee->hourly_rate ?? $payroll->hourly_rate ?? 100;
        $grossPay = $currentHours * $currentRate;
        
        $payroll->update([
            'total_hours' => $currentHours,
            'hourly_rate' => $currentRate,
            'gross_pay' => $grossPay,
            'status' => 'paid', 
            'pay_date' => now()
        ]);

        return redirect()->back()->with('success', 'Payroll marked as paid!');
    }

    public function bulkPay(Request $request)
    {
        $payrollIds = $request->input('payroll_ids', []);
        
        if (empty($payrollIds)) {
            return redirect()->back()->with('error', 'No payroll records selected.');
        }

        // Update each payroll individually to set correct hourly rate
        $month = request('month', now()->format('Y-m'));
        $payrolls = Payroll::with(['employee.attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->whereIn('id', $payrollIds)->where('status', 'pending')->get();
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($payrolls as $payroll) {
            // Check for incomplete attendance
            if ($this->hasIncompleteAttendance($payroll->employee->attendances)) {
                $skipped++;
                continue;
            }
            
            // Auto time-out any ongoing shifts before payment
            $this->autoTimeOutOngoingShifts($payroll->employee->attendances);
            
            // Calculate final values before marking as paid
            $currentHours = $this->calculateTotalHours($payroll->employee->attendances);
            $currentRate = $payroll->employee->hourly_rate ?? $payroll->hourly_rate ?? 100;
            $grossPay = $currentHours * $currentRate;
            
            $payroll->update([
                'total_hours' => $currentHours,
                'hourly_rate' => $currentRate,
                'gross_pay' => $grossPay,
                'status' => 'paid',
                'pay_date' => now()
            ]);
            $updated++;
        }
        
        $message = "Marked {$updated} payroll records as paid!";
        if ($skipped > 0) {
            $message .= " Skipped {$skipped} records with incomplete attendance.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function autoGenerate()
    {
        \Artisan::call('payroll:generate');
        $output = \Artisan::output();
        
        return redirect()->back()->with('success', 'Auto-generation completed! ' . strip_tags($output));
    }

    public function updateRate(Request $request, $id)
    {
        $request->validate(['hourly_rate' => 'required|numeric|min:0']);

        $employee = Employee::findOrFail($id);
        
        // Check if employee has any paid payrolls in current month
        $currentMonth = now()->format('Y-m');
        $hasPaidPayroll = Payroll::where('employee_id', $id)
                                ->where('period', $currentMonth)
                                ->where('status', 'paid')
                                ->exists();
        
        if ($hasPaidPayroll) {
            return redirect()->route('employee.payroll')
                           ->with('error', 'Cannot update hourly rate for employee with paid payroll in current month.');
        }
        
        $employee->hourly_rate = $request->hourly_rate;
        $employee->save();

        return redirect()->route('employee.payroll')->with('success', 'Hourly rate updated successfully.');
    }

    public function storeDeduction(Request $request)
    {
        $request->validate([
            'payroll_id' => 'required|exists:payrolls,id',
            'type' => 'required|in:late,violation',
            'time_unit' => 'nullable|in:minutes,hours',
            'duration' => 'nullable|integer|min:1',
            'reason' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0'
        ]);

        $amount = 0;
        
        if ($request->type === 'late') {
            $duration = $request->duration;
            if ($request->time_unit === 'minutes') {
                $amount = $duration * 2; // 2 pesos per minute
            } else if ($request->time_unit === 'hours') {
                $amount = $duration * 60; // 1 peso per minute = 60 pesos per hour
            }
        } else if ($request->type === 'violation') {
            $amount = $request->amount;
        }

        Deduction::create([
            'payroll_id' => $request->payroll_id,
            'type' => $request->type,
            'time_unit' => $request->time_unit,
            'duration' => $request->duration,
            'reason' => $request->reason,
            'amount' => $amount
        ]);

        return redirect()->back()->with('success', 'Deduction added successfully!');
    }

    public function getDeductions($id)
    {
        $payroll = Payroll::with('deductions')->findOrFail($id);
        
        return response()->json([
            'deductions' => $payroll->deductions,
            'total' => $payroll->deductions->sum('amount')
        ]);
    }

    public function deleteDeduction($id)
    {
        $deduction = Deduction::findOrFail($id);
        $deduction->delete();
        
        return response()->json(['success' => true]);
    }

    private function calculateTotalHours($attendances)
    {
        $totalHours = 0;
        
        foreach ($attendances as $attendance) {
            if ($attendance->time_in) {
                $timeIn = strtotime($attendance->time_in);
                
                // If time_out exists, use it; otherwise use current time for ongoing shift
                if ($attendance->time_out) {
                    $timeOut = strtotime($attendance->time_out);
                } else {
                    // Use current time for ongoing shift
                    $timeOut = time();
                }
                
                if ($timeOut < $timeIn) {
                    $timeOut += 24 * 3600;
                }

                $seconds = $timeOut - $timeIn;
                $hours = $seconds / 3600;
                $totalHours += max(0, $hours);
            }
        }
        return round($totalHours, 2);
    }

    private function hasIncompleteAttendance($attendances)
    {
        // Only consider it incomplete if there are old records without time_out
        // Current day ongoing shifts are allowed
        $today = now()->format('Y-m-d');
        
        foreach ($attendances as $attendance) {
            if ($attendance->time_in && !$attendance->time_out && $attendance->date != $today) {
                return true;
            }
        }
        return false;
    }

    private function autoTimeOutOngoingShifts($attendances)
    {
        $currentTime = now()->format('H:i:s');
        
        foreach ($attendances as $attendance) {
            if ($attendance->time_in && !$attendance->time_out) {
                $attendance->update(['time_out' => $currentTime]);
            }
        }
    }
}
