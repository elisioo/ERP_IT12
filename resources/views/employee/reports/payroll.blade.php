@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-money-bill me-2 text-success"></i> Payroll Report
            </h5>
            <small class="text-muted">Payroll summary and salary breakdown</small>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    <!-- Period Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Period (Month)</label>
                    <input type="month" name="period" value="{{ $period }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.payroll.pdf', request()->query()) }}" class="btn btn-danger ms-2">
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
                    <h3 class="text-primary">{{ $payrolls->count() }}</h3>
                    <small class="text-muted">Employees</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ number_format($totalHours, 1) }}</h3>
                    <small class="text-muted">Total Hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">₱{{ number_format($totalGrossPay, 2) }}</h3>
                    <small class="text-muted">Total Gross Pay</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $payrolls->where('status', 'paid')->count() }}</h3>
                    <small class="text-muted">Paid</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Hourly Rate</th>
                            <th>Total Hours</th>
                            <th>Gross Pay</th>
                            <th>Status</th>
                            <th>Pay Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                            <td>₱{{ number_format($payroll->hourly_rate, 2) }}</td>
                            <td>{{ $payroll->total_hours }} hrs</td>
                            <td>₱{{ number_format($payroll->gross_pay, 2) }}</td>
                            <td>
                                <span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td>{{ $payroll->pay_date ? $payroll->pay_date->format('M d, Y') : '--' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No payroll records found for this period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection