<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 16px; margin: 10px 0; }
        .period { font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">Korean Diner Davao</div>
        <div class="report-title">Attendance Report</div>
        <div class="period">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</div>
    </div>

    <table>
        <thead>
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
                <td class="text-center">{{ $attendance->time_in ?? '--' }}</td>
                <td class="text-center">{{ $attendance->time_out ?? '--' }}</td>
                <td class="text-center">
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
                <td class="text-center">
                    @if($attendance->time_in && $attendance->time_out)
                        <span class="badge badge-success">Complete</span>
                    @elseif($attendance->time_in)
                        <span class="badge badge-warning">Clocked In</span>
                    @else
                        <span class="badge badge-danger">No Record</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No attendance records found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        Generated on {{ now()->format('M d, Y H:i:s') }}
    </div>
</body>
</html>