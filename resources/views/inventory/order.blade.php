@extends('layout.inventory_app')

@section('content')


<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Order Management</h5>
            <small class="text-muted">Manage your orders efficiently</small>
        </div>

        <div class="d-flex gap-2">
            <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                <i class="fa-solid fa-chart-line"></i> Generate Report
            </a>

            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="fa-solid fa-box-archive"></i> View Archived
            </a>
            <!-- Create Order -->
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Create Order
            </a>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 mb-4">
                <div
                    class="card-header bg-white fw-bold d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Orders List</span>
                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <!-- 🔍 Search Box -->
                        <form method="GET" action="{{ route('orders.index') }}" class="d-flex align-items-center">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm me-2" placeholder="Search orders..."
                                style="width: 160px;">
                            <input type="hidden" name="perPage" value="{{ $perPage }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <input type="hidden" name="sort" value="{{ request('sort', 'desc') }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>

                        <!-- ⚙️ Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 200px;">
                                <form method="GET" action="{{ route('orders.index') }}">
                                    <li class="mb-2 fw-bold small text-muted">Status</li>
                                    <li>
                                        <select name="status" class="form-select form-select-sm mb-2"
                                            onchange="this.form.submit()">
                                            <option value="">All</option>
                                            <option value="pending"
                                                {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing"
                                                {{ request('status') == 'processing' ? 'selected' : '' }}>Processing
                                            </option>
                                            <option value="completed"
                                                {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="canceled"
                                                {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled
                                            </option>
                                        </select>
                                    </li>
                                    <input type="hidden" name="perPage" value="{{ $perPage }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="sort" value="{{ request('sort', 'desc') }}">
                                </form>
                            </ul>
                        </div>

                        <!-- 🔽 Sort Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fa fa-sort"></i> Sort
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-2">
                                <form method="GET" action="{{ route('orders.index') }}">
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="perPage" value="{{ $perPage }}">
                                    <li>
                                        <button class="dropdown-item {{ request('sort') == 'asc' ? 'active' : '' }}"
                                            name="sort" value="asc">
                                            Oldest First
                                        </button>
                                    </li>
                                    <li>
                                        <button
                                            class="dropdown-item {{ request('sort', 'desc') == 'desc' ? 'active' : '' }}"
                                            name="sort" value="desc">
                                            Newest First
                                        </button>
                                    </li>
                                </form>
                            </ul>
                        </div>


                        <!-- Ellipsis Dropdown (Archive) -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button type="submit" form="archiveSelectionForm" class="dropdown-item"
                                        onclick="return confirm('Archive selected orders?');">
                                        Archive Selected
                                    </button>

                                </li>
                            </ul>
                        </div>

                    </div>
                </div>


                <form id="archiveSelectionForm" method="POST" action="{{ route('orders.archiveSelection') }}">
                    @csrf

                    <div class="card-body table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Order No.</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orderss as $order)
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $order->id }}"></td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>
                                        @foreach($order->lines as $line)
                                        {{ $line->menu->menu_name ?? 'Not available'}} (x{{ $line->quantity }})<br>
                                        @endforeach
                                    </td>
                                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if($order->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status === 'processing')
                                        <span class="badge bg-info text-dark">Processing</span>
                                        @elseif($order->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                        @elseif($order->status === 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-dark">View</a>
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>
            </form>
            <div class="d-flex justify-content-between align-items-center gap-4">
                <form method="GET" action="{{ route('orders.index') }}" class="d-flex align-items-center">
                    <label for="perPage" class="me-2 mb-0">Show:</label>
                    <select name="perPage" id="perPage" class="form-select form-select-sm me-2"
                        onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span>entries</span>
                </form>

                <div class="d-flex justify-content-end align-items-center mt-2">
                    {{ $orderss->appends(['perPage' => $perPage])->links('pagination::bootstrap-5') }}
                </div>
            </div>



        </div>

        <!-- RIGHT COLUMN (Menus Summary) -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    Menus
                    <span class="small text-muted">Trending & Latest</span>
                </div>
                <div class="card-body">
                    <!-- Trending -->
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-fire text-danger"></i> Trending</h6>
                    @forelse($trendingMenus as $menu)
                    <div class="mb-3 d-flex align-items-center border-bottom pb-2">
                        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : '/images/default-menu.jpg' }}"
                            class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $menu->menu_name }}</h6>
                            <small class="text-muted">₱{{ number_format($menu->price, 2) }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No trending menus yet.</p>
                    @endforelse

                    <!-- Latest -->
                    <h6 class="fw-bold mt-4 mb-3"><i class="fa-solid fa-bolt text-primary"></i> Latest</h6>
                    @forelse($latestMenus as $menu)
                    <div class="mb-3 d-flex align-items-center border-bottom pb-2">
                        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : '/images/default-menu.jpg' }}"
                            class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $menu->menu_name }}</h6>
                            <small class="text-muted">₱{{ number_format($menu->price, 2) }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No recent menus yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Archived Orders Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold" id="archiveModalLabel">
                    <i class="fa-solid fa-box-archive me-2"></i> Archived Orders
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if ($archivedOrders->isEmpty())
                <p class="text-center text-muted mb-0 py-4">No archived orders found.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order No.</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total (₱)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($archivedOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-secondary">Archived</span>
                                </td>
                                <td>
                                    <form action="{{ route('orders.restore', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    {{ $archivedOrders->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Modal Report-->
<div class="modal fade" id="analyticsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title">Generate Sales Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('orders.analytics') }}" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Checkbox Select All -->
<script>
document.getElementById('selectAll').addEventListener('change', function(e) {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
});

// Load archived orders via AJAX when modal opens
document.getElementById('viewArchivedBtn').addEventListener('click', function() {
    fetch("{{ route('orders.archived') }}")
        .then(response => response.text())
        .then(html => {
            document.getElementById('archivedOrdersTable').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('archivedOrdersTable').innerHTML =
                "<p class='text-danger'>Failed to load archived orders.</p>";
        });
});
</script>

@endsection