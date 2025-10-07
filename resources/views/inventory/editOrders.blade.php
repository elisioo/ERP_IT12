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

    <!-- Edit Order Form -->
    <form method="POST" action="{{ route('orders.update', $order->id) }}">
        @csrf
        @method('PUT')

        <!-- Order Info -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-secondary">Order Information</h6>
                <div class="row gy-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Order Number</label>
                        <input type="text" name="order_number" class="form-control" value="{{ $order->order_number }}"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ $order->customer_name }}"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Order Date</label>
                        <input type="date" name="order_date" class="form-control"
                            value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Total Amount (₱)</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control"
                            value="{{ $order->total_amount }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-secondary">Order Items</h6>

                @foreach ($order->lines ?? [] as $index => $line)
                <div class="row align-items-end mb-3 border-bottom pb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Menu Item</label>
                        <select name="items[{{ $index }}][menu_id]" class="form-select" required>
                            @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}" {{ $line->menu_id == $menu->id ? 'selected' : '' }}>
                                {{ $menu->menu_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Quantity</label>
                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control" min="1"
                            value="{{ $line->quantity }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Price (₱)</label>
                        <input type="number" step="0.01" name="items[{{ $index }}][price]" class="form-control"
                            value="{{ $line->price }}" required>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm px-3">
                <i class="fa-solid fa-floppy-disk"></i> Update Order
            </button>
        </div>
    </form>
</div>
@endsection