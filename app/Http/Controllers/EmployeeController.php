<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $dashboardData = Cache::remember('dashboard_data_v2', 300, function () {
            $today = now()->toDateString();
            $currentMonth = now()->format('Y-m');
            $employees = Employee::active()
                ->with(['attendances' => function($q) use ($today) {
                    $q->where('date', $today);
                }])
                ->get();

            $totalEmployees = $employees->count();
            $presentCount = $employees->filter(function($emp) {
                return $emp->attendances->whereNotNull('time_in')->count() > 0;
            })->count();
            $absentCount = $totalEmployees - $presentCount;

            $totalHours = Attendance::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->whereNotNull('time_in')
                ->whereNotNull('time_out')
                ->get()
                ->sum(function($attendance) {
                    $timeIn = strtotime($attendance->time_in);
                    $timeOut = strtotime($attendance->time_out);
                    if ($timeOut < $timeIn) $timeOut += 24 * 3600;
                    return ($timeOut - $timeIn) / 3600;
                });

            return [
                'totalEmployees' => $totalEmployees,
                'presentCount' => $presentCount,
                'absentCount' => $absentCount,
                'totalHours' => round($totalHours, 1),
                'employees' => $employees
            ];
        });

        $monthlyData = Cache::remember('dashboard_monthly_' . now()->format('Y-m'), 1800, function () {
            $currentMonth = now()->format('Y-m');

            return [
                'totalSalary' => Payroll::where('period', $currentMonth)->sum('gross_pay'),
                'recentEmployees' => Employee::active()->latest()->take(5)->get(),
                'employeeHours' => $this->getEmployeeHours()
            ];
        });
        $recentActivities = Cache::remember('dashboard_activities', 180, function () {
            return Attendance::with('employee')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(10)
                ->get();
        });

        $notice = $this->generateNotice(
            $dashboardData['absentCount'],
            $dashboardData['presentCount'],
            $dashboardData['totalEmployees']
        );

        return view('employee.dashboard', array_merge(
            $dashboardData,
            $monthlyData,
            ['recentActivities' => $recentActivities, 'notice' => $notice]
        ));
    }

    private function getEmployeeHours()
    {
        return Employee::active()
            ->with(['attendances' => function($q) {
                $q->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year)
                  ->whereNotNull('time_in')
                  ->whereNotNull('time_out');
            }])
            ->get()
            ->map(function($employee) {
                $totalHours = $employee->attendances->sum(function($attendance) {
                    $timeIn = strtotime($attendance->time_in);
                    $timeOut = strtotime($attendance->time_out);
                    if ($timeOut < $timeIn) $timeOut += 24 * 3600;
                    return ($timeOut - $timeIn) / 3600;
                });

                return [
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'hours' => round($totalHours, 1)
                ];
            })
            ->filter(fn($emp) => $emp['hours'] > 0)
            ->sortByDesc('hours')
            ->take(5)
            ->values();
    }

    private function generateNotice($absentCount, $presentCount, $totalEmployees)
    {
        if ($absentCount > ($totalEmployees * 0.3)) {
            return ['type' => 'danger', 'message' => 'High absenteeism today! ' . $absentCount . ' employees are absent.'];
        } elseif ($absentCount > 0) {
            return ['type' => 'warning', 'message' => $absentCount . ' employees have not clocked in today.'];
        } elseif ($presentCount === $totalEmployees && $totalEmployees > 0) {
            return ['type' => 'success', 'message' => 'Perfect attendance! All employees are present today.'];
        } else {
            return ['type' => 'info', 'message' => 'Check attendance records and ensure all employees clock in on time.'];
        }
    }
}
