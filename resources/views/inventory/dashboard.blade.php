@extends('layout.inventory_app')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 id="greeting" class="h5 fw-bold"></h5>
            <p class="text-muted mb-0" id="message"></p>
        </div>
        <a href="#" class="btn btn-primary btn-sm">+ Add Item</a>
    </div>

    <div class="row">
        <!-- Left Column (Main Content) -->
        <div class="col-lg-9">
            <!-- Info Alert -->
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>
                <div>Notice: Low-stock items need to be restocked!</div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <i class="fa-solid fa-box fa-2x text-primary"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Total Products</h6>
                                    <p class="fs-5 fw-bold mb-0">120</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <i class="fa-solid fa-triangle-exclamation fa-2x text-warning"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Low Stock</h6>
                                    <p class="fs-5 fw-bold mb-0">15</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <i class="fa-solid fa-ban fa-2x text-danger"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Out of Stock</h6>
                                    <p class="fs-5 fw-bold mb-0">8</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <i class="fa-solid fa-sack-dollar fa-2x text-dark"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Total Expenses</h6>
                                    <p class="fs-5 fw-bold mb-0">Php 5,000.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <i class="fa-solid fa-money-bill-trend-up fa-2x text-success"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Gross Profit</h6>
                                    <p class="fs-5 fw-bold mb-0">Php 8,200.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Inventory List</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Coca-Cola 1L</td>
                                <td>Beverages</td>
                                <td><span class="badge bg-success">50</span></td>
                                <td>₱45.00</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Lucky Me Pancit Canton</td>
                                <td>Noodles</td>
                                <td><span class="badge bg-warning text-dark">4</span></td>
                                <td>₱12.00</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Surf Powder 1kg</td>
                                <td>Detergent</td>
                                <td><span class="badge bg-danger">0</span></td>
                                <td>₱95.00</td>
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
            <!-- Calendar / Schedule -->

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        Schedules
                        <span style="font-size: 12px; color: blue;"><i class="fa-light fa-plus"></i> Add New</span>
                    </div>

                </div>
                <div class="card-body">
                    <p class="small text-muted">Upcoming Events</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-calendar-day text-warning me-2"></i> Stock Check</span>
                            <small>Tomorrow</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-truck text-info me-2"></i> Supplier Meeting</span>
                            <small>Oct 5</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-users text-success me-2"></i> Team Meeting</span>
                            <small>Oct 8</small>
                        </li>
                    </ul>
                </div>
            </div>



            <!-- Progress -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Stock Status</div>
                <div class="card-body">
                    <p>Restock Progress</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: 70%;">70%</div>
                    </div>
                    <p class="mb-0 small text-muted">70% of low-stock items restocked</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-body {
    padding: 0.75rem !important;
    /* default is 1rem */
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const greetingElement = document.getElementById("greeting");
    const messageElement = document.getElementById("message");
    const now = new Date();
    const hour = now.getHours();

    let greeting = "";
    let message = "";
    if (hour >= 5 && hour < 12) {
        greeting = "Good Morning, Admin 🌞";
        message = "Have a great day!";
    } else if (hour >= 12 && hour < 18) {
        greeting = "Good Afternoon, Admin 🌤️";
        message = "Hope you're having a productive day!";
    } else {
        greeting = "Good Evening, Admin 🌙";
        message = "Hope you had a great day!";
    }
    messageElement.textContent = message;
    greetingElement.textContent = greeting;


});
</script>
@endsection