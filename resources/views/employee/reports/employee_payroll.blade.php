@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-user me-2 text-primary"></i> Payroll Report - {{ $employee->first_name }} {{ $employee->last_name }}
            </h5>
            <small class="text-muted">Individual employee payroll details</small>
        </div>
        <div>
            <a href="{{ route('reports.payroll', request()->query()) }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Payroll Report
            </a>
            <a href="{{ route('reports.employee.payroll.pdf', ['employeeId' => $employee->id, 'period' => $period]) }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> PDF
            </a>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Employee Details</h6>
                    <p><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</p>
                    <p><strong>Email:</strong> {{ $employee->email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $employee->phone ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Period Summary</h6>
                    <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($period)->format('F Y') }}</p>
                    <p><strong>Total Hours:</strong> {{ number_format($totalHours, 2) }} hrs</p>
                    <p><strong>Gross Pay:</strong> ₱{{ number_format($totalGrossPay, 2) }}</p>
                    <p><strong>Total Deductions:</strong> ₱{{ number_format($totalDeductions, 2) }}</p>
                    <p><strong>Net Pay:</strong> <span class="text-success fw-bold">₱{{ number_format($totalNetPay, 2) }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Details -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Payroll Breakdown</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Hours Worked</th>
                            <th>Hourly Rate</th>
                            <th>Gross Pay</th>
                            <th>Deductions</th>
                            <th class="text-success fw-bold">Net Pay</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->created_at->format('M d, Y') }}</td>
                            <td>{{ $payroll->total_hours }} hrs</td>
                            <td>₱{{ number_format($payroll->hourly_rate, 2) }}</td>
                            <td>₱{{ number_format($payroll->gross_pay, 2) }}</td>
                            <td class="text-danger">₱{{ number_format($payroll->total_deductions, 2) }}</td>
                            <td class="text-success fw-bold">₱{{ number_format($payroll->net_pay, 2) }}</td>
                            <td>
                                <span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No payroll records found for this employee in the selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($payrolls->count() > 0)
                    <tfoot class="table-dark">
                        <tr>
                            <th>Total</th>
                            <th>{{ number_format($totalHours, 2) }} hrs</th>
                            <th>--</th>
                            <th>₱{{ number_format($totalGrossPay, 2) }}</th>
                            <th class="text-danger">₱{{ number_format($totalDeductions, 2) }}</th>
                            <th class="text-success fw-bold">₱{{ number_format($totalNetPay, 2) }}</th>
                            <th>--</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Deductions Details (if any) -->
    @if($payrolls->where('total_deductions', '>', 0)->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Deductions Breakdown</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                        @foreach($payroll->deductions as $deduction)
                        <tr>
                            <td>{{ $payroll->created_at->format('M d, Y') }}</td>
                            <td>{{ $deduction->description }}</td>
                            <td class="text-danger">₱{{ number_format($deduction->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
