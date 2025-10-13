<!DOCTYPE html>
<html>
<head>
    <title>Payroll Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 16px; margin: 10px 0; }
        .period { font-size: 12px; color: #666; }
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
        <div class="report-title">Payroll Report</div>
        <div class="period">{{ \Carbon\Carbon::parse($period . '-01')->format('F Y') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Total Employees:</strong> {{ $payrolls->count() }}</div>
        <div class="summary-item"><strong>Total Hours:</strong> {{ number_format($totalHours, 1) }}</div>
        <div class="summary-item"><strong>Total Gross Pay:</strong> PHP{{ number_format($totalGrossPay, 2) }}</div>
    </div>

    <table>
        <thead>
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
                <td class="text-right">PHP {{ number_format($payroll->hourly_rate, 2) }}</td>
                <td class="text-center">{{ $payroll->total_hours }} hrs</td>
                <td class="text-right">PHP {{ number_format($payroll->gross_pay, 2) }}</td>
                <td class="text-center">
                    <span class="badge {{ $payroll->status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </td>
                <td class="text-center">{{ $payroll->pay_date ? $payroll->pay_date->format('M d, Y') : '--' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No payroll records found for this period</td>
            </tr>
            @endforelse
            @if($payrolls->count() > 0)
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td class="text-center">{{ number_format($totalHours, 1) }} hrs</td>
                <td class="text-right">PHP {{ number_format($totalGrossPay, 2) }}</td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        Generated on {{ now()->format('M d, Y H:i:s') }}
    </div>
</body>
</html>
