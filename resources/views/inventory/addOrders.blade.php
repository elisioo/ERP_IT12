@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h5 class="fw-bold h5">Add Order</h5>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-right-from-bracket"></i> Back to Orders
        </a>
    </div>

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <!-- Order Number -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Order Number</label>
                        <input type="text" name="order_number" class="form-control" placeholder="ORD-001" required>
                    </div>

                    <!-- Customer Name -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="John Doe" required>
                    </div>

                    <!-- Order Date -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Order Date</label>
                        <input type="date" name="order_date" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="₱0.00"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa-solid fa-plus"></i> Add Order
            </button>
        </div>
    </form>
</div>
@endsection