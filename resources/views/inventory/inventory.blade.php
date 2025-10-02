@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5"> Inventory</h5>
            <p class="text-muted mb-0">Monitor stock levels and manage your menu items efficiently.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Item
        </a>
    </div>

    <!-- Inventory Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Items</h6>
                        <h4 class="fw-bold">120</h4>
                    </div>
                    <i class="fa-solid fa-boxes fa-2x text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Low Stock</h6>
                        <h4 class="fw-bold text-warning">15</h4>
                    </div>
                    <i class="fa-solid fa-exclamation-triangle fa-2x text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Out of Stock</h6>
                        <h4 class="fw-bold text-danger">3</h4>
                    </div>
                    <i class="fa-solid fa-box-open fa-2x text-danger"></i>
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
                        <th>
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Stock Level</th>
                        <th>Price (₱)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>Kimchi Fried Rice</td>
                        <td>Rice</td>
                        <td>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 80%;"></div>
                            </div>
                            <small>80/100</small>
                        </td>
                        <td>₱150</td>
                        <td><span class="badge bg-success">In Stock</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>Spicy Pork Bulgogi</td>
                        <td>Meat</td>
                        <td>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 20%;"></div>
                            </div>
                            <small>5/25</small>
                        </td>
                        <td>₱320</td>
                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="select-item"></td>
                        <td>Seafood Pancake</td>
                        <td>Seafood</td>
                        <td>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <small>0/20</small>
                        </td>
                        <td>₱250</td>
                        <td><span class="badge bg-danger">Out of Stock</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-dark">Edit</a>
                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                        </td>
                    </tr>
                    <!-- Add more items dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Select All Checkbox Script -->
@push('scripts')
<script>
document.getElementById('select-all').addEventListener('click', function() {
    const checked = this.checked;
    document.querySelectorAll('.select-item').forEach(cb => cb.checked = checked);
});
</script>
@endpush
@endsection