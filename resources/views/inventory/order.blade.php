@extends('layout.inventory_app')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Order Management</h5>
            <p class="text-muted mb-0">Orders ></p>
        </div>
        <a href="#" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Create Order</a>
    </div>
    <!-- Order Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Orders</h6>
                    <h4 class="fw-bold">200</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">On Process</h6>
                    <h4 class="fw-bold text-warning">45</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Completed</h6>
                    <h4 class="fw-bold text-success">140</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Canceled</h6>
                    <h4 class="fw-bold text-danger">15</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-9">
            <!-- Orders Table -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    Orders List
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order No.</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>#ORD-1001</td>
                                <td>Maria Santos</td>
                                <td>Bulgogi Beef, Kimchi</td>
                                <td>₱630.00</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <a href="{{ route('orders.details') }}" class="btn btn-sm btn-outline-dark">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>#ORD-1002</td>
                                <td>Juan Dela Cruz</td>
                                <td>Bibimbap, Gochujang</td>
                                <td>₱520.00</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <a href="{{ route('orders.details') }}" class="btn btn-sm btn-outline-dark">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-3">
            <!-- Trending Menus -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    Trending Menus
                    <span class="small text-muted">This Week</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center border-bottom pb-2">
                        <img src="/images/bulgogi.jpg" class="rounded me-2"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">Bulgogi Beef</h6>
                            <small class="text-muted">₱480.00 | 120 sold</small>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center border-bottom pb-2">
                        <img src="/images/bibimbap.jpg" class="rounded me-2"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">Bibimbap</h6>
                            <small class="text-muted">₱250.00 | 95 sold</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="/images/kimchi.jpg" class="rounded me-2"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">Kimchi</h6>
                            <small class="text-muted">₱150.00 | 150 sold</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Summary -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Monthly Summary</div>
                <div class="card-body">
                    <p class="mb-1">This Month: <strong>310</strong></p>
                    <p class="mb-0">This Year: <strong>2,840</strong></p>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection