@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Inventory</h5>
            <p class="text-muted mb-0">Monitor stock levels and manage your menu items efficiently.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="fa-solid fa-box-archive"></i> View Archived
            </a>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#restockModal">
                <i class="fa-solid fa-box-open"></i> Restock
            </a>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-boxes fa-2x text-primary me-3"></i>
                    <div>
                        <h6 class="text-muted mb-2">Total Items</h6>
                        <h4 class="fw-bold">{{ $totalItems }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-exclamation-triangle fa-2x text-warning me-3"></i>
                    <div>
                        <h6 class="text-muted mb-2">Low Stock</h6>
                        <h4 class="fw-bold text-warning">{{ $lowStockCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-box-open fa-2x text-danger me-3"></i>
                    <div>
                        <h6 class="text-muted mb-2">Out of Stock</h6>
                        <h4 class="fw-bold text-danger">{{ $outOfStockCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">Inventory Items</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Unit Price (₱)</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                        <td>₱{{ number_format($item->cost_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $item->quantity == 0 ? 'danger' : ($item->quantity < 10 ? 'warning text-dark' : 'success') }}">
                                {{ $item->quantity == 0 ? 'Out of Stock' : ($item->quantity < 10 ? 'Low Stock' : 'In Stock') }}
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                            <form action="{{ route('inventory.archive', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                    <i class="fa-solid fa-box-archive"></i> Archive
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No items found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Archived Items Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel">Archived Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($archivedItems->isEmpty())
                    <p class="text-muted text-center mb-0">No archived items found.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Unit Price (₱)</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archivedItems as $archived)
                                <tr>
                                    <td>{{ $archived->item_name }}</td>
                                    <td>{{ $archived->category->category_name ?? 'N/A' }}</td>
                                    <td>₱{{ number_format($archived->cost_price, 2) }}</td>
                                    <td>{{ $archived->quantity }}</td>
                                    <td>{{ $archived->unit }}</td>
                                    <td>
                                        <form action="{{ route('inventory.restore', $archived->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fa-solid fa-rotate-left"></i> Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Restock Modal (Add Item) -->
    <div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="item_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Price (₱)</label>
                            <input type="number" step="0.01" class="form-control" name="cost_price" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control" name="unit" value="pcs">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection