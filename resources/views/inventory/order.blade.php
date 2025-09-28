@extends('layout.inventory_app')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom">
        <div>
            <h1 class="fw-bold h5">Order Management</h1>
            <p class="text-muted">
                Manage your orders efficiently with our comprehensive order management system.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 80vh;">

        <!-- Menu Cards -->
        <div class="d-flex justify-content-center gap-4 mt-2">
            <!-- Order List Card -->
            <div class="card border p-4 text-center" style="width: 200px; cursor: pointer;" onclick="location.href='#'">
                <i class="fa-solid fa-list fa-3x mb-3 text-danger"></i>
                <h5 class="fw-bold">Order List</h5>
                <p class="text-muted">View and manage all orders</p>
            </div>
            <!-- Create Order Card -->
            <div class="card border p-4 text-center" style="width: 200px; cursor: pointer;" onclick="location.href='#'">
                <i class="fa-solid fa-plus fa-3x mb-3 text-danger"></i>
                <h5 class="fw-bold">Create Order</h5>
                <p class="text-muted">Add new orders to the system</p>
            </div>
            <!-- Reports Card -->
            <div class="card border p-4 text-center" style="width: 200px; cursor: pointer;" onclick="location.href='#'">
                <i class="fa-solid fa-chart-line fa-3x mb-3 text-danger"></i>
                <h5 class="fw-bold">Reports</h5>
                <p class="text-muted">Generate order reports</p>
            </div>
        </div>



    </div>
</div>

@endsection