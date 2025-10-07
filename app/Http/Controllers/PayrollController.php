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

        $employees = Employee::with(['attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        foreach ($employees as $employee) {
            $totalHours = $this->calculateTotalHours($employee->attendances);
            $hourlyRate = $employee->hourly_rate ?? 100;
            $grossPay = $totalHours * $hourlyRate;

            Payroll::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $month],
                [
                    'total_hours' => $totalHours,
                    'hourly_rate' => $hourlyRate,
                    'gross_pay' => $grossPay,
                    'status' => 'pending'
                ]
            );
        }

        return redirect()->route('employee.payroll', ['month' => $month])
                        ->with('success', 'Payroll generated successfully!');
    }

    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update(['status' => 'paid', 'pay_date' => now()]);

        return redirect()->back()->with('success', 'Payroll marked as paid!');
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
