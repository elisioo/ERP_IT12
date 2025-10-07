@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-clock me-2 text-primary"></i> Attendance Report
            </h5>
            <small class="text-muted">Detailed attendance records and statistics</small>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    <!-- Date Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.attendance.pdf', request()->query()) }}" class="btn btn-danger ms-2">
                        <i class="fa-solid fa-file-pdf me-1"></i> PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $attendances->count() }}</h3>
                    <small class="text-muted">Total Records</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $attendances->whereNotNull('time_out')->count() }}</h3>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $attendances->whereNull('time_out')->count() }}</h3>
                    <small class="text-muted">Incomplete</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $attendances->groupBy('employee_id')->count() }}</h3>
                    <small class="text-muted">Employees</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                            <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
                            <td>{{ $attendance->time_in ?? '--' }}</td>
                            <td>{{ $attendance->time_out ?? '--' }}</td>
                            <td>
                                @if($attendance->time_in && $attendance->time_out)
                                    @php
                                        $timeIn = strtotime($attendance->time_in);
                                        $timeOut = strtotime($attendance->time_out);
                                        if ($timeOut < $timeIn) $timeOut += 24 * 3600;
                                        $hours = round(($timeOut - $timeIn) / 3600, 2);
                                    @endphp
                                    {{ $hours }} hrs
                                @else
                                    --
                                @endif
                            </td>
                            <td>
                                @if($attendance->time_in && $attendance->time_out)
                                    <span class="badge bg-success">Complete</span>
                                @elseif($attendance->time_in)
                                    <span class="badge bg-warning">Clocked In</span>
                                @else
                                    <span class="badge bg-danger">No Record</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No attendance records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection