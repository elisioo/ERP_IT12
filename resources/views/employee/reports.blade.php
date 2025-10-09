@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-chart-bar me-2 text-info"></i> Reports & Analytics
            </h5>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5>Total Employees</h5>
                    <h3>{{ $totalEmployees }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>Today's Attendance</h5>
                    <h3>{{ $totalAttendanceToday }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5>Attendance Rate</h5>
                    <h3>{{ $totalEmployees > 0 ? round(($totalAttendanceToday / $totalEmployees) * 100) : 0 }}%</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5>This Month Payroll</h5>
                    <h3>₱{{ number_format($monthlyPayroll->last()->total ?? 0, 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Weekly Attendance Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Employee Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Monthly Payroll Summary (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="payrollChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-clock fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Attendance Report</h5>
                    <p class="card-text">Generate detailed attendance reports with date range filtering</p>
                    <a href="{{ route('reports.attendance') }}" class="btn btn-primary">
                        <i class="fa-solid fa-file-alt me-1"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-money-bill fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Payroll Report</h5>
                    <p class="card-text">Generate payroll summaries and salary breakdowns by period</p>
                    <a href="{{ route('reports.payroll') }}" class="btn btn-success">
                        <i class="fa-solid fa-file-alt me-1"></i> View Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Weekly Attendance Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($weeklyAttendance->pluck('date')) !!},
        datasets: [{
            label: 'Daily Attendance',
            data: {!! json_encode($weeklyAttendance->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Employee Status Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(collect($employeeStatus)->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode(collect($employeeStatus)->pluck('count')) !!},
            backgroundColor: ['#36A2EB', '#FF6384']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Payroll Bar Chart
const payrollCtx = document.getElementById('payrollChart').getContext('2d');
new Chart(payrollCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyPayroll->pluck('month')) !!},
        datasets: [{
            label: 'Payroll Amount',
            data: {!! json_encode($monthlyPayroll->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endsection
