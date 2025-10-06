@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h5 class="fw-bold h5">Edit Order</h5>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-right-from-bracket"></i> Back to Orders
        </a>
    </div>

    <form method="POST" action="{{ route('orders.update', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <!-- Order Number -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Order Number</label>
                        <input type="text" name="order_number" class="form-control" value="{{ $order->order_number }}"
                            required>
                    </div>

                    <!-- Customer Name -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ $order->customer_name }}"
                            required>
                    </div>

                    <!-- Order Date -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Order Date</label>
                        <input type="date" name="order_date" class="form-control"
                            value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control"
                            value="{{ $order->total_amount }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Update Order
            </button>
        </div>
    </form>
</div>
@endsection