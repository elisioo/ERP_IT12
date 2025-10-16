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
            @elseif($order->status == 'canceled') bg-danger
            @endif
            px-3 py-2">
                {{ ucfirst($order->status) }}
            </span>
            <a href="{{ route('orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-secondary">
                <i class="fa fa-print"></i> Print Invoice
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->lines as $line)
                            <tr>
                                <td>{{ $line->menu->menu_name ?? 'Unknown Item' }}</td>
                                <td>{{ $line->quantity }}</td>
                                <td>{{ $line->notes ?? '-' }}</td>
                                <td>₱{{ number_format($line->menu->price ?? 0, 2) }}</td>
                                <td>₱{{ number_format($line->price, 2) }}</td>
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
                    <p class="mb-1"><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Order Date:</strong> {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M d, Y') : 'N/A' }}</p>
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
                        @elseif($order->status == 'canceled')
                        <li>
                            <span class="badge bg-danger"><i class="fa-solid fa-xmark"></i></span>
                            <span class="ms-2">Canceled</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Status Update Buttons (Only if not completed or canceled) -->
            @if(!in_array($order->status, ['completed', 'canceled']))
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Update Status</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.update', $order->id) }}" onsubmit="return confirm('Are you sure you want to update the status of this order?');">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields to maintain order data -->
                        <input type="hidden" name="customer_name" value="{{ $order->customer_name }}">
                        <input type="hidden" name="order_date" value="{{ $order->order_date }}">
                        
                        <!-- Include existing order items -->
                        @foreach($order->lines as $index => $line)
                            <input type="hidden" name="items[{{ $index }}][menu_id]" value="{{ $line->menu_id }}">
                            <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $line->quantity }}">
                        @endforeach
                        
                        <div class="d-grid gap-2">
                            @if($order->status == 'pending')
                            <button type="submit" name="status" value="processing" class="btn btn-warning">
                                <i class="fa-solid fa-clock"></i> Mark as On Process
                            </button>
                            @endif
                            
                            @if($order->status != 'completed')
                            <button type="submit" name="status" value="completed" class="btn btn-success">
                                <i class="fa-solid fa-check"></i> Mark as Completed
                            </button>
                            @endif
                            
                            <button type="submit" name="status" value="canceled" class="btn btn-danger">
                                <i class="fa-solid fa-xmark"></i> Cancel Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted">
                    <i class="fa-solid fa-ban fa-2x mb-2"></i>
                    <p class="mb-0">
                        @if($order->status == 'completed')
                            This order has been completed
                        @else
                            This order has been canceled
                        @endif
                    </p>
                    <small class="text-muted">No further status changes allowed</small>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-3 d-grid gap-2">
                @if(!in_array($order->status, ['completed', 'canceled']))
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-edit"></i> Edit Order
                </a>
                @else
                <button class="btn btn-secondary" disabled title="Cannot edit {{ $order->status }} orders">
                    <i class="fa-solid fa-lock"></i> Edit Order (Locked)
                </button>
                @endif
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection