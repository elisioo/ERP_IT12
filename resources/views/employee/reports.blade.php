@extends('layout.employee.employee_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5 d-flex align-items-center">
                <i class="fa-solid fa-chart-bar me-2 text-info"></i> Reports
            </h5>
            <small class="text-muted">Generate attendance and payroll reports</small>
        </div>
    </div>

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
@endsection