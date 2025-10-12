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
        $today = now()->toDateString();
        $employees = Employee::active()->get();
        
        // Get today's attendance
        $todayAttendance = Attendance::where('date', $today)
            ->whereIn('employee_id', $employees->pluck('id'))
            ->get();
            
        $totalEmployees = $employees->count();
        $presentCount = $todayAttendance->whereNotNull('time_in')->count();
        $absentCount = $totalEmployees - $presentCount;
        
        // Calculate total hours for current month
        $totalHours = Attendance::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->get()
            ->sum(function($attendance) {
                try {
                    $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                    $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                    return $timeOut->diffInMinutes($timeIn) / 60;
                } catch (\Exception $e) {
                    return 0;
                }
            });
            
        $dashboardData = [
            'totalEmployees' => $totalEmployees,
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
            'totalHours' => round($totalHours, 1),
            'employees' => $employees
        ];

        $currentMonth = now()->format('Y-m');
        $totalSalary = Payroll::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('gross_pay');
        
        // If no payroll data, calculate from attendance and hourly rates
        if ($totalSalary == 0) {
            $totalSalary = Employee::active()
                ->whereNotNull('hourly_rate')
                ->get()
                ->sum(function($employee) {
                    $monthlyHours = Attendance::where('employee_id', $employee->id)
                        ->whereMonth('date', now()->month)
                        ->whereYear('date', now()->year)
                        ->whereNotNull('time_in')
                        ->whereNotNull('time_out')
                        ->get()
                        ->sum(function($attendance) {
                            try {
                                $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                return $timeOut->diffInMinutes($timeIn) / 60;
                            } catch (\Exception $e) {
                                return 0;
                            }
                        });
                    return $monthlyHours * ($employee->hourly_rate ?? 100);
                });
        }
        $recentEmployees = Employee::active()->latest()->take(5)->get();
        $employeeHours = [
            'today' => $this->getEmployeeHours('today'),
            'week' => $this->getEmployeeHours('week'), 
            'month' => $this->getEmployeeHours('month')
        ];
        
        $monthlyData = [
            'totalSalary' => $totalSalary,
            'recentEmployees' => $recentEmployees,
            'employeeHours' => $employeeHours
        ];
        $recentActivities = Attendance::with('employee')
            ->whereDate('date', '>=', now()->subDays(7))
            ->latest('date')
            ->take(10)
            ->get();

        $notice = $this->generateNotice(
            $dashboardData['absentCount'],
            $dashboardData['presentCount'],
            $dashboardData['totalEmployees']
        );

        return view('employee.dashboard', array_merge(
            $dashboardData,
            $monthlyData,
            ['recentActivities' => $recentActivities, 'notice' => $notice, 'allEmployees' => $employees]
        ));
    }

    private function getEmployeeHours($period = 'month')
    {
        $employees = Employee::active()->get();
        
        $query = Attendance::whereNotNull('time_in')->whereNotNull('time_out');
        
        switch($period) {
            case 'today':
                $query->whereDate('date', now()->toDateString());
                break;
            case 'week':
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
            default:
                $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
                break;
        }
        
        $attendances = $query->get()->groupBy('employee_id');
            
        return $employees->map(function($employee) use ($attendances) {
            $employeeAttendances = $attendances->get($employee->id, collect());
            
            $totalHours = $employeeAttendances->sum(function($attendance) {
                try {
                    if (!$attendance->time_in || !$attendance->time_out) {
                        return 0;
                    }
                    
                    $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                    $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                    
                    // Handle same day calculation
                    $hours = $timeOut->diffInMinutes($timeIn) / 60;
                    return $hours > 0 ? $hours : 0;
                } catch (\Exception $e) {
                    return 0;
                }
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
