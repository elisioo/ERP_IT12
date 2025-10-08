<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('employee.reports');
    }

    public function attendance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $attendances = Attendance::with('employee')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        return view('employee.reports.attendance', compact('attendances', 'startDate', 'endDate'));
    }

    public function payroll(Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));
        $employees = Employee::active()->with(['attendances' => function($q) use ($period) {
            $q->whereYear('date', Carbon::parse($period)->year)
              ->whereMonth('date', Carbon::parse($period)->month);
        }])->get();

        $payrolls = collect();
        foreach ($employees as $employee) {
            $totalHours = $this->calculateTotalHours($employee->attendances);
            $hourlyRate = $employee->hourly_rate ?? 100;
            $grossPay = $totalHours * $hourlyRate;
            $savedPayroll = Payroll::where('employee_id', $employee->id)
                                  ->where('period', $period)
                                  ->first();

            $payrolls->push((object)[
                'employee' => $employee,
                'total_hours' => $totalHours,
                'hourly_rate' => $hourlyRate,
                'gross_pay' => $grossPay,
                'status' => $savedPayroll->status ?? 'pending',
                'pay_date' => $savedPayroll->pay_date ?? null
            ]);
        }

        $totalGrossPay = $payrolls->sum('gross_pay');
        $totalHours = $payrolls->sum('total_hours');

        return view('employee.reports.payroll', compact('payrolls', 'period', 'totalGrossPay', 'totalHours'));
    }

    private function calculateTotalHours($attendances)
    {
        $totalHours = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->time_in && $attendance->time_out) {
                $timeIn = strtotime($attendance->time_in);
                $timeOut = strtotime($attendance->time_out);

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

    public function attendancePdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $attendances = Attendance::with('employee')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $pdf = Pdf::loadView('employee.reports.pdf.attendance', compact('attendances', 'startDate', 'endDate'));
        return $pdf->download('attendance-report-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    public function payrollPdf(Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));

        $payrolls = Payroll::with('employee')
            ->where('period', $period)
            ->orderBy('gross_pay', 'desc')
            ->get();

        $totalGrossPay = $payrolls->sum('gross_pay');
        $totalHours = $payrolls->sum('total_hours');

        $pdf = Pdf::loadView('employee.reports.pdf.payroll', compact('payrolls', 'period', 'totalGrossPay', 'totalHours'));
        return $pdf->download('payroll-report-' . $period . '.pdf');
    }
}
