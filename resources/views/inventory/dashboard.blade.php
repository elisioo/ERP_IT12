@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-lg-9">
            <!-- Info Alert -->
            <div class="alert alert-info d-flex align-items-center shadow-sm" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>
                <div>Notice: Some fresh ingredients need to be reordered soon!</div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                @php
                $stats = [
                ['icon' => 'fa-bowl-food', 'color' => 'text-primary', 'title' => 'Total Ingredients', 'value' =>
                $totalIngredients ?? 85],
                ['icon' => 'fa-triangle-exclamation', 'color' => 'text-warning', 'title' => 'Low Stock', 'value' =>
                $lowStock ?? 12],
                ['icon' => 'fa-ban', 'color' => 'text-danger', 'title' => 'Out of Stock', 'value' => $outOfStock ?? 5],
                ['icon' => 'fa-sack-dollar', 'color' => 'text-success', 'title' => 'Expenses', 'value' => '₱' .
                number_format($expenses ?? 35000, 0)]
                ];
                @endphp

                @foreach($stats as $stat)
                <div class="col-sm-6 col-md-3">
                    <div class="card shadow-sm border-0 hover-shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid {{ $stat['icon'] }} fa-2x {{ $stat['color'] }} me-3"></i>
                                <div>
                                    <h6 class="card-title text-muted mb-1">{{ $stat['title'] }}</h6>
                                    <p class="fs-5 fw-bold mb-0">{{ $stat['value'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Gross Sales Card (with chart) -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h6 class="text-muted mb-1">Gross Sales</h6>
                        <h4 class="fw-bold text-success">₱{{ number_format($grossSales ?? 120000, 0) }}</h4>
                        <small class="text-muted">This Month</small>
                    </div>

                </div>
            </div>

            <!-- Summary of Sold Meals -->
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
                            @foreach($soldMeals ?? [
                            ['name' => 'Bulgogi Set', 'category' => 'Main Dish', 'qty' => 45, 'sales' => 18000],
                            ['name' => 'Kimchi Fried Rice', 'category' => 'Main Dish', 'qty' => 70, 'sales' => 21000],
                            ['name' => 'Tteokbokki', 'category' => 'Snack', 'qty' => 60, 'sales' => 15000],
                            ['name' => 'Samgyeopsal (per set)', 'category' => 'Main Dish', 'qty' => 80, 'sales' =>
                            40000],
                            ] as $index => $meal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $meal['name'] }}</td>
                                <td>{{ $meal['category'] }}</td>
                                <td>{{ $meal['qty'] }}</td>
                                <td>₱{{ number_format($meal['sales'], 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">Sales Trend</div>
                <div class="card-body">
                    <div style="height: 150px; width: 250px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Schedules -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">Schedules</div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Upcoming Events</p>
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

            <!-- Stock Status -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Stock Status</div>
                <div class="card-body">
                    <p class="mb-2">Restock Progress</p>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success fw-semibold" style="width: 60%">60%</div>
                    </div>
                    <small class="text-muted">60% of low-stock items restocked</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Greeting Script -->
<script>
const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // X-axis
        datasets: [{
            label: 'Sales',
            data: [25000, 30000, 32000, 33000], // Y-axis
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.15)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#28a745',
            pointRadius: 3,
            pointHoverRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#333',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 8
            },
            title: {
                display: true,
                text: 'Weekly Sales Trend',
                color: '#333',
                font: {
                    size: 13,
                    weight: 'bold'
                },
                padding: {
                    bottom: 10
                }
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Weeks',
                    color: '#333',
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                },
                grid: {
                    color: '#eee'
                },
                ticks: {
                    color: '#333',
                    font: {
                        size: 11
                    }
                }
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'Sales (₱)',
                    color: '#333',
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                },
                beginAtZero: true,
                grid: {
                    color: '#eee'
                },
                ticks: {
                    color: '#333',
                    font: {
                        size: 11
                    }
                }
            }
        }
    }
});
</script>

@endsection