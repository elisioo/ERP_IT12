@extends('layout.employee.employee_app')

@section('content')
<div class="d-flex">
    <!-- Dashboard Content -->
    <div class="flex-grow-1 p-4">
        <h4>Dashboard – Employee</h4>

        <!-- Notice -->
        <div class="alert alert-dark text-white bg-primary">
            <i class="bi bi-info-circle"></i> Notice: Check the attendance!
        </div>

        <!-- Top Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-dark p-3">Employees</div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-dark p-3">Absents</div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-dark p-3">Leave</div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-dark p-3">Salary</div>
            </div>
        </div>

        <!-- Total Employees & Leaderboard -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card text-white bg-dark p-3">
                    <h6>Total Employees</h6>
                    <p class="m-0">Placeholder for chart or table</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light p-3">
                    <h6>Leaderboard</h6>
                    <p class="m-0">Placeholder for leaderboard content</p>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card bg-light p-3">
            <h6>Recent Activities</h6>
            <p class="m-0">Placeholder for logs or actions</p>
        </div>
    </div>
</div>
@endsection
