@extends('layout.employee.employee_app')

@section('content')
<div class="d-flex">
    <!-- Dashboard Content -->
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="d-flex align-items-center mb-0">
                    <i class="fa-solid fa-chart-line me-2 text-primary"></i> Employee Dashboard
                </h4>
                <small class="text-muted">Overview of employee management system</small>
            </div>
        </div>

        <!-- Notice -->
        <div class="alert alert-{{ $notice['type'] }} d-flex align-items-center">
            @if($notice['type'] === 'danger')
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
            @elseif($notice['type'] === 'warning')
                <i class="fa-solid fa-exclamation-circle me-2"></i>
            @elseif($notice['type'] === 'success')
                <i class="fa-solid fa-check-circle me-2"></i>
            @else
                <i class="fa-solid fa-info-circle me-2"></i>
            @endif
            {{ $notice['message'] }}
        </div>

        <!-- Top Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary p-3">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-users fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Employees</h6>
                            <h4 class="mb-0">{{ $totalEmployees }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger p-3">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-user-xmark fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Absents</h6>
                            <h4 class="mb-0">{{ $absentCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info p-3">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-clock fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Total Hours</h6>
                            <h4 class="mb-0">{{ $totalHours }}h</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success p-3">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-peso-sign fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Salary</h6>
                            <h4 class="mb-0">₱{{ number_format($totalSalary, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Employees & Leaderboard -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card p-3">
                    <h6 class="d-flex align-items-center">
                        <i class="fa-solid fa-users me-2"></i> Recent Employees
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            @forelse($recentEmployees as $employee)
                            <tr>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td><small class="text-muted">₱{{ number_format($employee->hourly_rate ?? 100, 2) }}/hr</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-muted">No employees found</td></tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="d-flex align-items-center">
                        <i class="fa-solid fa-chart-pie me-2 text-warning"></i> Employee Hours (This Month)
                    </h6>
                    <div class="text-center" style="height: 200px; position: relative;">
                        <canvas id="hoursChart" 
                                data-employees="{{ $employeeHours->pluck('name')->implode(',') }}" 
                                data-hours="{{ $employeeHours->pluck('hours')->implode(',') }}"></canvas>
                    </div>
                    <div class="mt-2">
                        @foreach($employeeHours->take(3) as $emp)
                            <small class="d-block">{{ $emp['name'] }}: {{ $emp['hours'] }}h</small>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card p-3">
            <h6 class="d-flex align-items-center">
                <i class="fa-solid fa-clock-rotate-left me-2 text-info"></i> Recent Activities
            </h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    @forelse($recentActivities as $activity)
                    <tr>
                        <td>{{ $activity->employee->first_name }} {{ $activity->employee->last_name }}</td>
                        <td>
                            @if($activity->time_in && $activity->time_out)
                                <span class="badge bg-success">Completed</span>
                            @elseif($activity->time_in)
                                <span class="badge bg-warning">Clocked In</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $activity->date }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-muted">No recent activities</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@vite('resources/js/dashboard.js')
