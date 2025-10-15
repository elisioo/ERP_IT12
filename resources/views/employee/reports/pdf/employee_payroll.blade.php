<!DOCTYPE html>
<html>
<head>
    <title>Employee Payroll Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 16px; margin: 10px 0; }
        .period { font-size: 12px; color: #666; }
        .employee-info { margin: 20px 0; border: 1px solid #ddd; padding: 15px; }
        .summary { margin: 20px 0; }
        .summary-item { display: inline-block; margin-right: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">Korean Diner Davao</div>
        <div class="report-title">Employee Payroll Report</div>
        <div class="period">{{ \Carbon\Carbon::parse($period . '-01')->format('F Y') }}</div>
    </div>

    <div class="employee-info">
        <h5>Employee Information</h5>
        <div><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</div>
        <div><strong>Email:</strong> {{ $employee->email ?? 'N/A' }}</div>
        <div><strong>Phone:</strong> {{ $employee->phone ?? 'N/A' }}</div>
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Period:</strong> {{ \Carbon\Carbon::parse($period . '-01')->format('F Y') }}</div>
        <div class="summary-item"><strong>Total Hours:</strong> {{ number_format($totalHours, 2) }}</div>
        <div class="summary-item"><strong>Gross Pay:</strong> PHP {{ number_format($totalGrossPay, 2) }}</div>
        <div class="summary-item"><strong>Total Deductions:</strong> PHP {{ number_format($totalDeductions, 2) }}</div>
        <div class="summary-item"><strong>Net Pay:</strong> PHP {{ number_format($totalNetPay, 2) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Hours Worked</th>
                <th>Hourly Rate</th>
                <th>Gross Pay</th>
                <th>Deductions</th>
                <th style="font-weight: bold;">Net Pay</th>
                <th>Status</th>
                <th>Pay Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payrolls as $payroll)
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($payroll->period)->format('M Y') }}</td>
                <td class="text-center">{{ $payroll->total_hours }} hrs</td>
                <td class="text-right">PHP {{ number_format($payroll->hourly_rate, 2) }}</td>
                <td class="text-right">PHP {{ number_format($payroll->gross_pay, 2) }}</td>
                <td class="text-right">PHP {{ number_format($payroll->total_deductions, 2) }}</td>
                <td class="text-right" style="font-weight: bold;">PHP {{ number_format($payroll->net_pay, 2) }}</td>
                <td class="text-center">
                    <span class="badge {{ $payroll->status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </td>
                <td class="text-center">{{ $payroll->pay_date ? $payroll->pay_date->format('M d, Y') : '--' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No payroll records found for this employee in the selected period</td>
            </tr>
            @endforelse
            @if($payrolls->count() > 0)
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-center">{{ number_format($totalHours, 2) }} hrs</td>
                <td></td>
                <td class="text-right">PHP {{ number_format($totalGrossPay, 2) }}</td>
                <td class="text-right">PHP {{ number_format($totalDeductions, 2) }}</td>
                <td class="text-right" style="font-weight: bold;">PHP {{ number_format($totalNetPay, 2) }}</td>
                <td></td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($payrolls->where('total_deductions', '>', 0)->count() > 0)
    <h5 style="margin-top: 30px;">Deductions Breakdown</h5>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
            @foreach($payroll->deductions as $deduction)
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($payroll->period)->format('M Y') }}</td>
                <td>{{ $deduction->description }}</td>
                <td class="text-right">PHP {{ number_format($deduction->amount, 2) }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
    @endif

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        Generated on {{ now()->format('M d, Y H:i:s') }}
    </div>
</body>
</html>
