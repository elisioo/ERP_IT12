@extends('layout.employee.employee_app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 d-flex align-items-center">
                <i class="fa-solid fa-clock me-2 text-primary"></i> Employee Attendance
            </h2>
            <small class="text-muted">Track and manage employee work hours</small>
        </div>
        <div>
            <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#employeeListModal">
                <i class="fa-solid fa-users me-1"></i> List of Employees
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTbody">
                        @foreach ($employees as $employee)
                            @php
                                $attendance = $employee->attendances->first();
                            @endphp
                            <tr data-name="{{ strtolower($employee->first_name . ' ' . $employee->last_name) }}">
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td colspan="2">
                                    <form class="attendance-form d-flex align-items-center gap-2" data-id="{{ $employee->id }}">
                                        <input type="hidden" class="attendance-date" value="{{ $selectedDate }}">
                                        <input type="time" class="form-control time-in-input" value="{{ $attendance->time_in ?? '' }}" {{ $attendance && $attendance->time_out ? 'readonly' : '' }}>
                                        <input type="time" class="form-control time-out-input" value="{{ $attendance->time_out ?? '' }}" {{ $attendance && $attendance->time_out ? 'readonly' : '' }}>
                                        @if(!$attendance || !$attendance->time_out)
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        @else
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('employee.delete', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to archive this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning btn-sm">Archive</button>
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

@endsection
@vite('resources/js/attendance.js')
