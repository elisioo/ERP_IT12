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

    <!-- Check if order is completed or canceled -->
    @if(in_array($order->status, ['completed', 'canceled']))
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="fa-solid fa-triangle-exclamation fa-2x me-3"></i>
        <div>
            <h5 class="alert-heading mb-1">Order Cannot Be Edited</h5>
            <p class="mb-0">This order has been <strong>{{ $order->status }}</strong> and cannot be modified. Only pending or processing orders can be edited.</p>
        </div>
    </div>

    <!-- Read-only Order Summary -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3 text-secondary">Order Information (Read-Only)</h6>
            <div class="row gy-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Order Number</label>
                    <input type="text" class="form-control" value="{{ $order->order_number }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Customer Name</label>
                    <input type="text" class="form-control" value="{{ $order->customer_name }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Order Date</label>
                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Status</label>
                    <input type="text" class="form-control" value="{{ ucfirst($order->status) }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Total Amount (₱)</label>
                    <input type="text" class="form-control" value="₱{{ number_format($order->total_amount, 2) }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-eye"></i> View Order Details
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders List
        </a>
    </div>

    @else
    <!-- Flash Messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $order->customer_name) }}"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Order Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" class="form-control"
                            value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="canceled" {{ old('status', $order->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                        <small class="text-muted">Note: Changing to "Canceled" will restore inventory</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-secondary mb-0">Order Items</h6>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addOrderItem()">
                        <i class="fa-solid fa-plus"></i> Add Item
                    </button>
                </div>

                <div id="order-items-container" data-item-index="{{ count($order->lines) }}">
                    @foreach ($order->lines ?? [] as $index => $line)
                    <div class="order-item row align-items-end mb-3 border-bottom pb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Menu Item <span class="text-danger">*</span></label>
                            <select name="items[{{ $index }}][menu_id]" class="form-select menu-select" required onchange="updatePrice(this)">
                                <option value="">Select Menu Item</option>
                                @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}" 
                                    data-price="{{ $menu->price }}"
                                    data-stock="{{ $menu->inventory->quantity ?? 0 }}"
                                    {{ old("items.$index.menu_id", $line->menu_id) == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->menu_name }} - ₱{{ number_format($menu->price, 2) }} (Stock: {{ $menu->inventory->quantity ?? 0 }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity-input" 
                                min="1" value="{{ old("items.$index.quantity", $line->quantity) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Unit Price (₱)</label>
                            <input type="number" step="0.01" class="form-control unit-price" 
                                value="{{ $line->menu->price ?? 0 }}" readonly>
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm w-100" onclick="removeOrderItem(this)">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($order->lines) == 0)
                <p class="text-muted text-center">No items added yet. Click "Add Item" to start.</p>
                @endif
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                Cancel
            </a>
            <button type="submit" class="btn btn-success btn-sm px-3">
                <i class="fa-solid fa-floppy-disk"></i> Update Order
            </button>
        </div>
    </form>

    <script>
        let itemIndex = parseInt(document.getElementById('order-items-container').dataset.itemIndex, 10) || 0;

        function addOrderItem() {
            const container = document.getElementById('order-items-container');
            const newItem = `
                <div class="order-item row align-items-end mb-3 border-bottom pb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Menu Item <span class="text-danger">*</span></label>
                        <select name="items[${itemIndex}][menu_id]" class="form-select menu-select" required onchange="updatePrice(this)">
                            <option value="">Select Menu Item</option>
                            @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}" 
                                data-price="{{ $menu->price }}"
                                data-stock="{{ $menu->inventory->quantity ?? 0 }}">
                                {{ $menu->menu_name }} - ₱{{ number_format($menu->price, 2) }} (Stock: {{ $menu->inventory->quantity ?? 0 }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Unit Price (₱)</label>
                        <input type="number" step="0.01" class="form-control unit-price" value="0" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm w-100" onclick="removeOrderItem(this)">
                            <i class="fa-solid fa-trash text-danger"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newItem);
            itemIndex++;
        }

        function removeOrderItem(button) {
            const items = document.querySelectorAll('.order-item');
            if (items.length > 1) {
                button.closest('.order-item').remove();
            } else {
                alert('At least one item is required for the order.');
            }
        }

        function updatePrice(select) {
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.dataset.price || 0;
            const row = select.closest('.order-item');
            const priceInput = row.querySelector('.unit-price');
            priceInput.value = parseFloat(price).toFixed(2);
        }
    </script>
    @endif
</div>
@endsection