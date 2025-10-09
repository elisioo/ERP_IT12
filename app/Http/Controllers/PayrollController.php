<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $employees = Employee::active()->with(['attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        $payrolls = collect();
        foreach ($employees as $employee) {
            $attendanceCount = $employee->attendances->count();
            $totalHours = $this->calculateTotalHours($employee->attendances);
            $hourlyRate = $employee->hourly_rate ?? 100;
            $grossPay = $totalHours * $hourlyRate;

            \Log::info('Employee: ' . $employee->first_name . ', Attendances: ' . $attendanceCount . ', Hours: ' . $totalHours);

            $savedPayroll = Payroll::where('employee_id', $employee->id)
                                  ->where('period', $month)
                                  ->first();

            $payrolls->push((object)[
                'id' => $savedPayroll->id ?? null,
                'employee' => $employee,
                'total_hours' => $totalHours,
                'hourly_rate' => $hourlyRate,
                'gross_pay' => $grossPay,
                'status' => $savedPayroll->status ?? 'pending',
                'pay_date' => $savedPayroll->pay_date ?? null
            ]);
        }

        return view('employee.payroll', compact('payrolls', 'month'));
    }

    public function generate(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $autoPay = $request->boolean('auto_pay', false);

        $employees = Employee::active()->with(['attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        $generated = 0;
        $updated = 0;

        foreach ($employees as $employee) {
            $totalHours = $this->calculateTotalHours($employee->attendances);
            $hourlyRate = $employee->hourly_rate ?? 100;
            $grossPay = $totalHours * $hourlyRate;

            $payroll = Payroll::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $month],
                [
                    'total_hours' => $totalHours,
                    'hourly_rate' => $hourlyRate,
                    'gross_pay' => $grossPay,
                    'status' => $autoPay ? 'paid' : 'pending',
                    'pay_date' => $autoPay ? now() : null
                ]
            );

            if ($payroll->wasRecentlyCreated) {
                $generated++;
            } else {
                $updated++;
            }
        }

        $message = "Payroll generated! Created: {$generated}, Updated: {$updated}";
        if ($autoPay) {
            $message .= " (Auto-paid)";
        }

        return redirect()->route('employee.payroll', ['month' => $month])
                        ->with('success', $message);
    }

    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update(['status' => 'paid', 'pay_date' => now()]);

        return redirect()->back()->with('success', 'Payroll marked as paid!');
    }

    public function bulkPay(Request $request)
    {
        $payrollIds = $request->input('payroll_ids', []);
        
        if (empty($payrollIds)) {
            return redirect()->back()->with('error', 'No payroll records selected.');
        }

        $updated = Payroll::whereIn('id', $payrollIds)
                         ->where('status', 'pending')
                         ->update([
                             'status' => 'paid',
                             'pay_date' => now()
                         ]);

        return redirect()->back()->with('success', "Marked {$updated} payroll records as paid!");
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
        $employee->hourly_rate = $request->hourly_rate;
        $employee->save();

        return redirect()->route('employee.payroll')->with('success', 'Hourly rate updated successfully.');
    }

    private function calculateTotalHours($attendances)
    {
        $totalHours = 0;
        foreach ($attendances as $attendance) {
            \Log::info('Processing attendance - Date: ' . $attendance->date . ', Time In: ' . $attendance->time_in . ', Time Out: ' . $attendance->time_out);

            if ($attendance->time_in && $attendance->time_out) {
                $timeIn = strtotime($attendance->time_in);
                $timeOut = strtotime($attendance->time_out);
                if ($timeOut < $timeIn) {
                    $timeOut += 24 * 3600;
                }

                $seconds = $timeOut - $timeIn;
                $hours = $seconds / 3600;
                $totalHours += max(0, $hours);

                \Log::info('Calculated hours for this attendance: ' . $hours);
            }
        }
        \Log::info('Total hours calculated: ' . $totalHours);
        return round($totalHours, 2);
    }
}
