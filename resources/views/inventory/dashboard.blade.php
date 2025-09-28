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
        <!-- Left Column -->
        <div class="col-lg-9">
            <!-- Info Alert -->
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>
                <div>Notice: Some fresh ingredients need to be reordered soon!</div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <i class="fa-solid fa-bowl-food fa-2x text-primary"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Total Ingredients</h6>
                                    <p class="fs-5 fw-bold mb-0">85</p>
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
                                    <p class="fs-5 fw-bold mb-0">12</p>
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
                                    <p class="fs-5 fw-bold mb-0">5</p>
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
                                    <i class="fa-solid fa-sack-dollar fa-2x text-success"></i>
                                </div>
                                <div class="col-9">
                                    <h6 class="card-title text-muted mb-1">Expenses</h6>
                                    <p class="fs-5 fw-bold mb-0">₱35,000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gross Sales Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Gross Sales</h6>
                        <h4 class="fw-bold text-success">₱120,000</h4>
                        <small class="text-muted">This Month</small>
                    </div>
                    <i class="fa-solid fa-chart-line fa-2x text-success"></i>
                </div>
            </div>

            <!-- Sold Meals Summary -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Summary of Sold Meals</span>
                    <select class="form-select form-select-sm w-auto" id="mealFilter">
                        <option value="day">Today</option>
                        <option value="month" selected>This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Meal Name</th>
                                <th>Category</th>
                                <th>Qty Sold</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Bulgogi Set</td>
                                <td>Main Dish</td>
                                <td>45</td>
                                <td>₱18,000</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Kimchi Fried Rice</td>
                                <td>Main Dish</td>
                                <td>70</td>
                                <td>₱21,000</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Tteokbokki</td>
                                <td>Snack</td>
                                <td>60</td>
                                <td>₱15,000</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Samgyeopsal (per set)</td>
                                <td>Main Dish</td>
                                <td>80</td>
                                <td>₱40,000</td>
                            </tr>
                        </tbody>
                    </table>
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
                                <td>Bulgogi Beef (1kg)</td>
                                <td>Meat</td>
                                <td><span class="badge bg-success">12</span></td>
                                <td>₱480.00</td>
                                <td> <a href="#" class="btn btn-sm btn-outline-dark">Edit</a> <a href="#"
                                        class="btn btn-sm btn-outline-danger">Delete</a> </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Kimchi (Jar)</td>
                                <td>Side Dish</td>
                                <td><span class="badge bg-warning text-dark">3</span></td>
                                <td>₱150.00</td>
                                <td> <a href="#" class="btn btn-sm btn-outline-dark">Edit</a> <a href="#"
                                        class="btn btn-sm btn-outline-danger">Delete</a> </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Gochujang (500g)</td>
                                <td>Sauce</td>
                                <td><span class="badge bg-success">20</span></td>
                                <td>₱220.00</td>
                                <td> <a href="#" class="btn btn-sm btn-outline-dark">Edit</a> <a href="#"
                                        class="btn btn-sm btn-outline-danger">Delete</a> </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Rice (25kg Sack)</td>
                                <td>Staple</td>
                                <td><span class="badge bg-danger">0</span></td>
                                <td>₱1,200.00</td>
                                <td> <a href="#" class="btn btn-sm btn-outline-dark">Edit</a> <a href="#"
                                        class="btn btn-sm btn-outline-danger">Delete</a> </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Seaweed Sheets (Pack of 50)</td>
                                <td>Ingredients</td>
                                <td><span class="badge bg-success">40</span></td>
                                <td>₱300.00</td>
                                <td> <a href="#" class="btn btn-sm btn-outline-dark">Edit</a> <a href="#"
                                        class="btn btn-sm btn-outline-danger">Delete</a> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-3">
            <!-- Calendar -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">Schedules</div>
                <div class="card-body">
                    <p class="small text-muted">Upcoming Events</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-truck text-info me-2"></i> Beef Delivery</span>
                            <small>Tomorrow</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-boxes-stacked text-warning me-2"></i> Rice Restock</span>
                            <small>Oct 5</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-bottle-water text-success me-2"></i> Beverage Supply</span>
                            <small>Oct 8</small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Stock Progress -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Stock Status</div>
                <div class="card-body">
                    <p>Restock Progress</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: 60%;">60%</div>
                    </div>
                    <p class="mb-0 small text-muted">60% of low-stock items restocked</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const greetingElement = document.getElementById("greeting");
    const messageElement = document.getElementById("message");
    const now = new Date();
    const hour = now.getHours();

    let greeting = "";
    let message = "";
    if (hour >= 5 && hour < 12) {
        greeting = "Good Morning, Chef 👨‍🍳";
        message = "Let's prepare delicious Korean dishes today!";
    } else if (hour >= 12 && hour < 18) {
        greeting = "Good Afternoon, Team 🌤️";
        message = "Hope customers are enjoying their meals!";
    } else {
        greeting = "Good Evening, Chef 🌙";
        message = "Great work today, time to wrap up!";
    }
    messageElement.textContent = message;
    greetingElement.textContent = greeting;
});
</script>
@endsection