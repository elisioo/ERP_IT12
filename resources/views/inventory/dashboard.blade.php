@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-lg-9">
            <!-- Info Alert -->
            @if($stockAlert)
            <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <div>{{ $stockAlert }}</div>
            </div>
            @endif


            <!-- Insight Summary Cards -->
            <div class="row g-3 mb-4">
                @php
                $stats = [
                [
                'icon' => 'fa-receipt',
                'color' => 'text-primary',
                'title' => 'Total Orders',
                'value' => $totalOrders ?? 150,
                'subtitle' => 'Orders processed this month'
                ],
                [
                'icon' => 'fa-fire-flame-curved',
                'color' => 'text-warning',
                'title' => 'Top-Selling Meal',
                'value' => $topSellingMeal->menu->menu_name ?? 'No Data',
                'subtitle' => 'Best performer this month'
                ],
                [
                'icon' => 'fa-chart-line',
                'color' => 'text-info',
                'title' => 'Avg. Order Value',
                'value' => '₱' . number_format($avgOrderValue ?? 250, 2),
                'subtitle' => 'Average per transaction'
                ],
                [
                'icon' => 'fa-sack-dollar',
                'color' => 'text-success',
                'title' => 'Total Revenue',
                'value' => '₱' . number_format($totalRevenue ?? 120000, 2),
                'subtitle' => 'This month\'s income'
                ]
                ];
                @endphp

                <div class="row g-3 mb-4">
                    @foreach($stats as $stat)
                    <div class="col-sm-6 col-md-3 d-flex">
                        <div class="card shadow-sm border-0 hover-shadow-sm flex-fill h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid {{ $stat['icon'] }} fa-2x {{ $stat['color'] }} me-3"></i>
                                    <div>
                                        <h6 class="card-title text-muted mb-1">{{ $stat['title'] }}</h6>
                                        <p class="fs-5 fw-bold mb-0">{{ $stat['value'] }}</p>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $stat['subtitle'] }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>


            <!-- Gross Sales Card (with chart) -->
            <!-- <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h6 class="text-muted mb-1">Gross Sales</h6>
                        <h4 class="fw-bold text-success">₱{{ number_format($grossSales ?? 120000, 0) }}</h4>
                        <small class="text-muted">This Month</small>
                    </div>

                </div>
            </div> -->

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
                            @foreach($soldMeals as $index => $meal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $meal->menu->menu_name ?? 'Unknown' }}</td>
                                <td>{{ $meal->menu->category->category_name ?? 'Uncategorized' }}</td>
                                <td>{{ $meal->total_qty }}</td>
                                <td>₱{{ number_format($meal->total_sales, 2) }}</td>
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
                        <div class="progress-bar bg-success fw-semibold"
                            style="width: {{ $stockStatus['percentage'] ?? 0 }}%">
                            {{ $stockStatus['percentage'] ?? 0 }}%
                        </div>
                    </div>
                    <small class="text-muted">{{ $stockStatus['text'] ?? 'No stock data available' }}</small>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('salesChart').getContext('2d');

// Pass PHP data to JS
const salesLabels = @json($salesTrend['labels']);
const salesValues = @json($salesTrend['values']);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [{
            label: 'Sales',
            data: salesValues,
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
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Sales (₱)',
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
            }
        }
    }
});
</script>


@endsection