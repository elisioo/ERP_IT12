@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Order ID <span class="text-primary">#{{ $order->order_number }}</span></h5>
        </div>
        <div>
            <span class="badge 
            @if($order->status == 'pending') bg-secondary
            @elseif($order->status == 'processing') bg-warning text-dark
            @elseif($order->status == 'completed') bg-success
            @elseif($order->status == 'cancelled') bg-danger
            @endif
            px-3 py-2">
                {{ ucfirst($order->status) }}
            </span>
            <a href="{{ route('orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-secondary">
                <i class="fa fa-print"></i> Print Invoice
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Order List -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Order List</div>
                <div class="card-body table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Notes</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->lines as $line)
                            <tr>
                                <td>{{ $line->menu->menu_name ?? 'Unknown Item' }}</td>
                                <td>{{ $line->quantity }}</td>
                                <td>{{ $line->notes ?? '-' }}</td>
                                <td>₱{{ number_format($line->price, 2) }}</td>
                                <td>₱{{ number_format($line->quantity * $line->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No items found for this order.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="text-end mt-3">
                        <h6 class="fw-bold">Total Amount: ₱{{ number_format($order->total_amount, 2) }}</h6>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Customer</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Service Type:</strong> {{ $order->service_type ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Order Tracking -->

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Order Tracking</div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                            <span class="ms-2">Order Placed - {{ $order->created_at->format('h:i A') }}</span>
                        </li>
                        @if($order->status != 'pending')
                        <li class="mb-3">
                            <span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                            <span class="ms-2">Order Confirmed</span>
                        </li>
                        @endif
                        @if($order->status == 'processing')
                        <li class="mb-3">
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i></span>
                            <span class="ms-2">On Process (Preparing)</span>
                        </li>
                        @elseif($order->status == 'completed')
                        <li>
                            <span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                            <span class="ms-2">Completed</span>
                        </li>
                        @elseif($order->status == 'cancelled')
                        <li>
                            <span class="badge bg-danger"><i class="fa-solid fa-xmark"></i></span>
                            <span class="ms-2">Cancelled</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Status Update Buttons -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Update Status</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="d-grid gap-2">
                            <button type="submit" name="status" value="processing" class="btn btn-warning">
                                Mark as On Process
                            </button>
                            <button type="submit" name="status" value="completed" class="btn btn-success">
                                Mark as Completed
                            </button>
                            <button type="submit" name="status" value="cancelled" class="btn btn-danger">
                                Cancel Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection