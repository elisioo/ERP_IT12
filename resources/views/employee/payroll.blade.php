@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-money-bill me-2 text-success"></i> Payroll
            </h5>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex align-items-center">
                <label class="me-2"><i class="fa-solid fa-calendar me-1"></i>Month:</label>
                <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm me-2" style="width: 150px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter me-1"></i>Filter
                </button>
            </form>
            <div class="btn-group" role="group">
                <form method="POST" action="{{ route('payroll.generate') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa-solid fa-calculator me-1"></i>Refresh
                    </button>
                </form>
                <form method="POST" action="{{ route('payroll.generate') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="auto_pay" value="1">
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-magic-wand-sparkles me-1"></i>Auto-Pay
                    </button>
                </form>
                <form method="POST" action="{{ route('payroll.autoGenerate') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm" title="Run automated payroll generation">
                        <i class="fa-solid fa-robot me-1"></i>Auto
                    </button>
                </form>
                <a href="{{ route('reports.payroll', ['period' => $month]) }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fa-solid fa-file-pdf me-1"></i>Report
                </a>
            </div>
        </div>
    </div>

    @if($payrolls->isEmpty())
        <div class="alert alert-info">
            No payroll records found for {{ $month }}. Click "Generate" to create records.
        </div>
    @else
        <form id="bulkPayForm" method="POST" action="{{ route('payroll.bulkPay') }}">
            @csrf
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button type="button" id="selectAll" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-check-square me-1"></i>Select All
                    </button>
                    <button type="submit" id="bulkPayBtn" class="btn btn-sm btn-success" disabled>
                        <i class="fa-solid fa-money-bill me-1"></i>Pay Selected
                    </button>
                </div>
                <small class="text-muted">Total Gross: ₱{{ number_format($payrolls->sum('gross_pay'), 2) }} | Total Net: ₱{{ number_format($payrolls->sum(function($p) { return $p->net_pay ?? ($p->gross_pay - ($p->total_deductions ?? 0)); }), 2) }} | Total Deductions: ₱{{ number_format($payrolls->sum(function($p) { return $p->total_deductions ?? 0; }), 2) }}</small>
            </div>
            <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0 text-muted">Showing {{ $payrolls->firstItem() }}–{{ $payrolls->lastItem() }} of {{ $payrolls->total() }} payroll records</p>
                <div class="d-flex justify-content-end">
                    {{ $payrolls->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="masterCheck"></th>
                            <th>Employee</th>
                            <th class="text-success fw-bold">Net Pay</th>
                            <th>Gross Pay</th>
                            <th>Deductions</th>
                            <th>Total Hours</th>
                            <th>Hourly Rate</th>
                            <th>Status</th>
                            <th>Pay Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach($payrolls as $payroll)
                    <tr>
                        <td>
                            @if($payroll->status == 'pending' && !(isset($payroll->has_incomplete_attendance) && $payroll->has_incomplete_attendance))
                                <input type="checkbox" name="payroll_ids[]" value="{{ $payroll->id }}" class="payroll-check">
                            @endif
                        </td>
                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        <td class="text-success">
                            @if($payroll->status == 'pending')
                                <span class="text-success fw-bold fs-5">₱{{ number_format($payroll->net_pay ?? ($payroll->gross_pay - ($payroll->total_deductions ?? 0)), 2) }}</span>
                                <small class="text-muted d-block">Live</small>
                            @else
                                <span class="text-success fw-bold fs-5">₱{{ number_format($payroll->net_pay ?? ($payroll->gross_pay - ($payroll->total_deductions ?? 0)), 2) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($payroll->status == 'pending')
                                <span class="text-primary fw-bold">₱{{ number_format($payroll->gross_pay, 2) }}</span>
                                <small class="text-muted d-block">Live</small>
                            @else
                                ₱{{ number_format($payroll->gross_pay, 2) }}
                            @endif
                        </td>
                        <td>
                            @if(($payroll->total_deductions ?? 0) > 0)
                                <button type="button" class="btn btn-link p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#deductionHistoryModal"
                                        data-payroll-id="{{ $payroll->id }}" data-employee-name="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}"
                                        data-payroll-status="{{ $payroll->status }}">
                                    ₱{{ number_format($payroll->total_deductions ?? 0, 2) }}
                                </button>
                            @else
                                ₱0.00
                            @endif
                        </td>
                        <td>
                            @if($payroll->status == 'pending')
                                @php
                                    $attendanceCount = $payroll->employee->attendances->count();
                                    $hasValidAttendance = $payroll->employee->attendances->where('time_in', '!=', null)->where('time_out', '!=', null)->count();
                                @endphp
                                <span class="text-primary fw-bold">{{ $payroll->total_hours }} hrs</span>
                                <small class="text-muted d-block">{{ $attendanceCount }} total, {{ $hasValidAttendance }} complete</small>
                            @else
                                {{ $payroll->total_hours }} hrs
                            @endif
                        </td>
                        <td>
                            @if($payroll->status == 'pending')
                                @php
                                    $currentRate = $payroll->employee->hourly_rate ?? $payroll->hourly_rate ?? 100;
                                @endphp
                                <button class="btn btn-link p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#editRateModal"
                                        data-id="{{ $payroll->employee->id }}" data-name="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}"
                                        data-rate="{{ $currentRate }}">
                                    ₱{{ number_format($currentRate, 2) }}
                                </button>
                            @else
                                <span class="text-muted">₱{{ number_format($payroll->hourly_rate, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $payroll->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </td>
                        <td>{{ $payroll->pay_date ? $payroll->pay_date->format('M d, Y') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($payroll->status == 'pending')
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#deductionModal"
                                            data-payroll-id="{{ $payroll->id }}" data-employee-name="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}">
                                        <i class="fa-solid fa-minus me-1"></i>Deduct
                                    </button>
                                    @if(isset($payroll->has_incomplete_attendance) && $payroll->has_incomplete_attendance)
                                        <button type="button" class="btn btn-sm btn-secondary" disabled title="Cannot pay - incomplete attendance (missing time out)">
                                            <i class="fa-solid fa-clock me-1"></i>Incomplete
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('payroll.markPaid', $payroll->id) }}" class="d-inline" onsubmit="return confirm('Mark this payroll as paid?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa-solid fa-check me-1"></i>Pay
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-success"><i class="fa-solid fa-check-circle"></i> Paid</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </form>
    @endif
</div>

@include('employee.modals.edit_rate')
@include('employee.modals.deduction')
@include('employee.modals.deduction_history')

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const masterCheck = document.getElementById('masterCheck');
    let payrollChecks = document.querySelectorAll('.payroll-check');
    const bulkPayBtn = document.getElementById('bulkPayBtn');
    const selectAllBtn = document.getElementById('selectAll');

    function updateBulkPayButton() {
        const checkedBoxes = document.querySelectorAll('.payroll-check:checked');
        bulkPayBtn.disabled = checkedBoxes.length === 0;
    }

    function attachEventListeners() {
        payrollChecks = document.querySelectorAll('.payroll-check');
        payrollChecks.forEach(check => {
            check.addEventListener('change', updateBulkPayButton);
        });
    }

    // Auto-refresh every minute for real-time calculations
    setInterval(function() {
        location.reload();
    }, 60000);

    masterCheck?.addEventListener('change', function() {
        payrollChecks.forEach(check => {
            check.checked = this.checked;
        });
        updateBulkPayButton();
    });

    attachEventListeners();

    payrollChecks.forEach(check => {
        check.addEventListener('change', updateBulkPayButton);
    });

    selectAllBtn?.addEventListener('click', function() {
        const allChecked = Array.from(payrollChecks).every(check => check.checked);
        payrollChecks.forEach(check => {
            check.checked = !allChecked;
        });
        masterCheck.checked = !allChecked;
        updateBulkPayButton();
        this.innerHTML = allChecked ?
            '<i class="fa-solid fa-check-square me-1"></i>Select All' :
            '<i class="fa-solid fa-square me-1"></i>Deselect All';
    });

    document.getElementById('bulkPayForm')?.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.payroll-check:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one payroll record.');
        } else {
            return confirm(`Are you sure you want to mark ${checkedBoxes.length} payroll record(s) as paid?`);
        }
    });
});
</script>

@vite('resources/js/payroll.js')
