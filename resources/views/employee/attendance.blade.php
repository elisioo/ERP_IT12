@extends('layout.employee.employee_app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 d-flex align-items-center">
                <i class="fa-solid fa-clock me-2 text-primary"></i> Employee Attendance
            </h2>
        </div>
        <div>
            <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#employeeListModal">
                <i class="fa-solid fa-users me-1"></i> List of Employees
            </button>
            <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#autoTimeoutModal">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Auto Time-Out
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fa-solid fa-user-plus me-1"></i> Add Employee
            </button>
        </div>
    </div>

    <div class="mb-3">
        <label for="filterDate" class="form-label">Select Date:</label>
        <input type="date" id="filterDate" class="form-control" value="{{ $selectedDate }}">
    </div>

    <div class="d-flex mb-3 justify-content-between">
        <div>
            <input type="text" id="searchInput" class="form-control" placeholder="Search employee...">
        </div>
        <div class="d-flex">
            <select id="sortSelect" class="form-select me-2">
                <option value="az">A–Z</option>
                <option value="za">Z–A</option>
            </select>
            <button id="resetFilters" class="btn btn-secondary">Reset</button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="mb-0 text-muted">Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }} employees</p>
        <div class="d-flex justify-content-end">
            {{ $employees->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTbody">
                        @foreach ($employees as $employee)
                            @php
                                $attendance = $employee->attendances->first();
                            @endphp
                            <tr data-name="{{ strtolower($employee->first_name . ' ' . $employee->last_name) }}" @if(!$attendance || !$attendance->time_in) class="border-danger" style="border-left: 4px solid #dc3545;" @endif>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td>
                                    <div class="time-info">
                                        <span class="time-display" id="time-in-{{ $employee->id }}">
                                            @if($attendance && $attendance->time_in)
                                                {{ \Carbon\Carbon::parse($attendance->time_in)->format('g:i A') }}
                                            @else
                                                --:--
                                            @endif
                                        </span>
                                        @if($attendance && $attendance->time_in)
                                            <small class="text-muted d-block" id="time-in-seconds-{{ $employee->id }}">
                                                {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i:s') }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="time-info">
                                        <span class="time-display" id="time-out-{{ $employee->id }}">
                                            @if($attendance && $attendance->time_out)
                                                {{ \Carbon\Carbon::parse($attendance->time_out)->format('g:i A') }}
                                            @else
                                                --:--
                                            @endif
                                        </span>
                                        @if($attendance && $attendance->time_out)
                                            <small class="d-block">
                                                @if($attendance->timeout_type === 'manual')
                                                    <span class="badge bg-primary">Manual</span>
                                                @elseif($attendance->timeout_type === 'auto_immediate')
                                                    <span class="badge bg-warning">Auto Immediate</span>
                                                @elseif($attendance->timeout_type === 'auto_scheduled')
                                                    <span class="badge bg-info">Auto Scheduled</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="duration-display" id="duration-{{ $employee->id }}">
                                        @if($attendance && $attendance->time_in && $attendance->time_out)
                                            @php
                                                $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                                $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                                $duration = $timeOut->diff($timeIn);
                                            @endphp
                                            {{ $duration->format('%H:%I:%S') }}
                                        @else
                                            --:--:--
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($selectedDate === now()->toDateString())
                                        @if(!$attendance)
                                            <button class="btn btn-success btn-sm toggle-attendance" data-id="{{ $employee->id }}">
                                                <i class="fa-solid fa-clock"></i> Time In
                                            </button>
                                        @elseif($attendance->time_in && !$attendance->time_out)
                                            <button class="btn btn-warning btn-sm toggle-attendance" data-id="{{ $employee->id }}">
                                                <i class="fa-solid fa-clock"></i> Time Out
                                            </button>
                                        @else
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Past Date</span>
                                    @endif

                                    <form action="{{ route('employee.delete', $employee->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to archive this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                            <i class="fa-solid fa-archive"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('employee.modals.add_employee')
@include('employee.modals.employee_list')
@include('employee.modals.auto_timeout')

@endsection
@vite('resources/js/attendance.js')
