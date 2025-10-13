@extends('layout.employee.employee_app')

@section('content')
<div class="d-flex">
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="d-flex align-items-center mb-0">
                    <i class="fa-solid fa-chart-line me-2 text-primary"></i> Employee Dashboard
                </h4>
            </div>
        </div>

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

        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="stats-card text-white bg-primary" style="cursor: pointer; transition: transform 0.2s;" data-bs-toggle="modal" data-bs-target="#employeeListModal" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Employees</h6>
                            <h2 class="stat-number mb-0">{{ $totalEmployees }}</h2>
                        </div>
                        <div class="text-end">
                            <i class="fa-solid fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <a href="{{ route('attendance.index') }}" class="text-decoration-none">
                    <div class="stats-card text-white bg-danger" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1 opacity-75">Absent Today</h6>
                                <h2 class="stat-number mb-0">{{ $absentCount }}</h2>
                            </div>
                            <div class="text-end">
                                <i class="fa-solid fa-user-xmark fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card text-white bg-info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Hours</h6>
                            <h2 class="stat-number mb-0">{{ $totalHours }}<small class="fs-6">h</small></h2>
                        </div>
                        <div class="text-end">
                            <i class="fa-solid fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card text-white bg-success">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Net Pay</h6>
                            <h2 class="stat-number mb-0">₱{{ number_format($totalSalary, 0) }}</h2>
                        </div>
                        <div class="text-end">
                            <i class="fa-solid fa-peso-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="d-flex align-items-center mb-0">
                            <i class="fa-solid fa-chart-pie me-2 text-warning"></i> Employee Hours
                        </h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary period-btn active" data-period="today">Today</button>
                            <button type="button" class="btn btn-outline-secondary period-btn" data-period="week">Week</button>
                            <button type="button" class="btn btn-outline-secondary period-btn" data-period="month">Month</button>
                        </div>
                    </div>
                    <div class="text-center" style="height: 200px; position: relative;">
                        <canvas id="hoursChart"
                                data-today="{{ json_encode($employeeHours['today']) }}"
                                data-week="{{ json_encode($employeeHours['week']) }}"
                                data-month="{{ json_encode($employeeHours['month']) }}"></canvas>
                        <div id="noDataMessage" class="d-flex align-items-center justify-content-center h-100 text-muted" style="display: none;">
                            <i class="fa-solid fa-chart-pie me-2"></i><span id="noDataText">No hours recorded</span>
                        </div>
                    </div>
                    <div class="mt-2" id="hoursList">
                    </div>
                </div>
            </div>
        </div>

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

@include('employee.modals.employee_list')

@endsection
@vite('resources/js/dashboard.js')
