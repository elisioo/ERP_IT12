@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="h5 fw-bold">Expenses Management</h5>
            <p class="text-muted mb-0">Track daily, weekly, and monthly expenses.</p>
        </div>
        <a href="{{ route('expenses.add') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Expense
        </a>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-9">
            <!-- Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Expenses (This Month)</h6>
                        <h4 class="fw-bold text-danger">₱45,800</h4>
                        <small class="text-muted">As of Sept 28, 2025</small>
                    </div>
                    <i class="fa-solid fa-receipt fa-2x text-danger"></i>
                </div>
            </div>

            <!-- Expense List -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Expense Records</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Sept 25, 2025</td>
                                <td>Utilities</td>
                                <td>Electricity Bill</td>
                                <td>₱12,000</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Sept 20, 2025</td>
                                <td>Supplies</td>
                                <td>Meat & Vegetables</td>
                                <td>₱18,500</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Sept 15, 2025</td>
                                <td>Rent</td>
                                <td>Monthly Shop Rent</td>
                                <td>₱15,300</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-3">
            <!-- Expense Breakdown -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">Expense Categories</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Rent <span class="badge bg-primary">₱15,300</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Utilities <span class="badge bg-success">₱12,000</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Supplies <span class="badge bg-warning text-dark">₱18,500</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Salaries <span class="badge bg-info">₱0</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Upcoming Payments -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Upcoming Payments</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item"><i class="fa-solid fa-building text-primary me-2"></i> Rent Due -
                            Oct 15</li>
                        <li class="list-group-item"><i class="fa-solid fa-lightbulb text-warning me-2"></i> Electricity
                            Bill - Oct 20</li>
                        <li class="list-group-item"><i class="fa-solid fa-users text-info me-2"></i> Staff Salary - Oct
                            25</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection