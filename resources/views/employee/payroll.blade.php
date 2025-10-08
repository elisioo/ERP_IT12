@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-money-bill me-2 text-success"></i> Payroll
            </h5>
            <small class="text-muted">Manage employee salary and payments</small>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex align-items-center">
                <label class="me-2"><i class="fa-solid fa-calendar me-1"></i>Month:</label>
                <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm me-2" style="width: 150px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
            </form>
            <form method="POST" action="{{ route('payroll.generate') }}">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-calculator me-1"></i>Generate Payroll
                </button>
            </form>
        </div>
    </div>

    @if($payrolls->isEmpty())
        <div class="alert alert-info">
            No payroll records found for {{ $month }}. Click "Generate Payroll" to create records.
        </div>
    @else
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        <td>
                            <button class="btn btn-link p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#editRateModal" 
                                    data-id="{{ $payroll->employee->id }}" data-name="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}" 
                                    data-rate="{{ $payroll->hourly_rate }}">
                                ₱{{ number_format($payroll->hourly_rate, 2) }}
                            </button>
                        </td>
                        <td>{{ $payroll->total_hours }} hrs</td>
                        <td>₱{{ number_format($payroll->gross_pay, 2) }}</td>
                        <td>
                            <span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </td>
                        <td>{{ $payroll->pay_date ? $payroll->pay_date->format('M d, Y') : '-' }}</td>
                        <td>
                            @if($payroll->status == 'pending')
                                @if($payroll->id)
                                    <form method="POST" action="{{ route('payroll.markPaid', $payroll->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fa-solid fa-check me-1"></i>Mark Paid
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Generate first</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@include('employee.modals.edit_rate')

@endsection
@vite('resources/js/payroll.js')