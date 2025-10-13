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
        // Employee Analytics
        $totalEmployees = Employee::active()->count();
        $totalAttendanceToday = Attendance::whereDate('date', today())->count();

        // Weekly Attendance Trend
        $weeklyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(*) as count')
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Employee Status Distribution
        $employeeStatus = [
            ['status' => 'Active', 'count' => Employee::active()->count()],
            ['status' => 'Archived', 'count' => Employee::archived()->count()]
        ];

        // Monthly Payroll Summary
        $monthlyPayroll = Payroll::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(net_pay) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('employee.reports', compact(
            'totalEmployees', 'totalAttendanceToday', 'weeklyAttendance',
            'employeeStatus', 'monthlyPayroll'
        ));
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

        $payrolls = Payroll::with('employee')
            ->where('period', $period)
            ->orderBy('gross_pay', 'desc')
            ->get();

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

    public function employeePayroll($employeeId, Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));

        $employee = Employee::findOrFail($employeeId);

        $payrolls = Payroll::with('employee', 'deductions')
            ->where('employee_id', $employeeId)
            ->where('period', $period)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalGrossPay = $payrolls->sum('gross_pay');
        $totalDeductions = $payrolls->sum('total_deductions');
        $totalNetPay = $payrolls->sum('net_pay');
        $totalHours = $payrolls->sum('total_hours');

        return view('employee.reports.employee_payroll', compact(
            'employee', 'payrolls', 'period', 'totalGrossPay', 'totalDeductions', 'totalNetPay', 'totalHours'
        ));
    }

    public function employeePayrollPdf($employeeId, Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));

        $employee = Employee::findOrFail($employeeId);

        $payrolls = Payroll::with('employee', 'deductions')
            ->where('employee_id', $employeeId)
            ->where('period', $period)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalGrossPay = $payrolls->sum('gross_pay');
        $totalDeductions = $payrolls->sum('total_deductions');
        $totalNetPay = $payrolls->sum('net_pay');
        $totalHours = $payrolls->sum('total_hours');

        $pdf = Pdf::loadView('employee.reports.pdf.employee_payroll', compact(
            'employee', 'payrolls', 'period', 'totalGrossPay', 'totalDeductions', 'totalNetPay', 'totalHours'
        ));
        return $pdf->download('payroll-report-' . $employee->first_name . '-' . $employee->last_name . '-' . $period . '.pdf');
    }
}
