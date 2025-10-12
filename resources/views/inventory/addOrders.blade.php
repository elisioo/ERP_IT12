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

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>There were some problems with your input:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        <!-- Order Details -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Customer Name -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="John Doe" required>
                    </div>

                    <!-- Order Date -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Order Date</label>
                        <input type="datetime-local" name="order_date" class="form-control" required>
                    </div>


                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span>Order Items</span>
                <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                    <i class="fa-solid fa-plus"></i> Add Item
                </button>
            </div>

            <div class="card-body" id="orderItems">
                <div class="row g-3 align-items-end mb-3 order-item">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Menu</label>
                        <select name="items[0][menu_id]" class="form-select menu-select" required>
                            <option value="">Select Menu</option>
                            @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}"
                                data-available="{{ $menu->inventory->quantity ?? 0 }}">
                                {{ $menu->menu_name }} - ₱{{ number_format($menu->price, 2) }} |
                                {{ $menu->inventory->quantity ?? 0 }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Quantity</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="items[0][quantity]" class="form-control quantity" min="1"
                                value="1" style="width: 100px;">
                            <small class="text-muted available-badge" style="min-width: 120px;">Available: 0</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Subtotal</label>
                        <input type="text" class="form-control subtotal" value="₱0.00" readonly>
                    </div>

                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-outline-danger removeItemBtn">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="card shadow-sm border-0">
            <div class="card-body text-end">
                <h5 class="fw-bold">Total: <span id="totalAmount">₱0.00</span></h5>
                <input type="hidden" name="total_amount" id="totalInput">
                <button type="submit" class="btn btn-success mt-2">
                    <i class="fa-solid fa-floppy-disk"></i> Save Order
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JS for Dynamic Order Items -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const addItemBtn = document.getElementById('addItemBtn');
    const orderItems = document.getElementById('orderItems');
    const totalDisplay = document.getElementById('totalAmount');
    const totalInput = document.getElementById('totalInput');

    // ✅ Function: Update available quantity and limit input
    function updateRowAvailability(row) {
        const select = row.querySelector('.menu-select');
        const qtyInput = row.querySelector('.quantity');
        const option = select.selectedOptions[0];
        const available = parseInt(option?.dataset?.available || 0);
        const availBadge = row.querySelector('.available-badge');

        availBadge.textContent = `Available: ${available}`;
        qtyInput.max = available > 0 ? available : 1;

        if (available === 0) {
            qtyInput.value = 0;
            qtyInput.disabled = true;
            availBadge.classList.add('text-danger');
        } else {
            qtyInput.disabled = false;
            availBadge.classList.remove('text-danger');
        }
    }

    // ✅ Function: Update subtotal and total
    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(row => {
            const subtotal = row.querySelector('.subtotal').value.replace('₱', '') || 0;
            total += parseFloat(subtotal);
        });
        totalDisplay.textContent = '₱' + total.toFixed(2);
        totalInput.value = total.toFixed(2);
    }

    // Add new row
    addItemBtn.addEventListener('click', () => {
        const template = document.querySelector('.order-item').cloneNode(true);
        template.querySelectorAll('input, select').forEach(input => {
            input.value = '';
            if (input.name.includes('items')) {
                input.name = input.name.replace(/\d+/, itemIndex);
            }
        });
        template.querySelector('.subtotal').value = '₱0.00';
        template.querySelector('.available-badge').textContent = 'Available: 0';
        orderItems.appendChild(template);
        itemIndex++;
    });

    // Remove row
    orderItems.addEventListener('click', e => {
        if (e.target.closest('.removeItemBtn')) {
            const item = e.target.closest('.order-item');
            if (document.querySelectorAll('.order-item').length > 1) {
                item.remove();
                updateTotals();
            }
        }
    });

    // Change events: menu or quantity
    orderItems.addEventListener('input', e => {
        if (e.target.classList.contains('menu-select') || e.target.classList.contains('quantity')) {
            const row = e.target.closest('.order-item');
            const select = row.querySelector('.menu-select');
            const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
            const qty = parseInt(row.querySelector('.quantity').value || 1);
            const available = parseInt(select.selectedOptions[0]?.dataset.available || 0);

            // Prevent exceeding stock
            if (qty > available) {
                row.querySelector('.quantity').value = available;
            }

            const subtotal = price * Math.min(qty, available);
            row.querySelector('.subtotal').value = '₱' + subtotal.toFixed(2);
            updateTotals();
        }
    });

    // When selecting a new menu
    orderItems.addEventListener('change', e => {
        if (e.target.classList.contains('menu-select')) {
            const row = e.target.closest('.order-item');
            updateRowAvailability(row);
        }
    });

    // Initial update
    document.querySelectorAll('.order-item').forEach(row => updateRowAvailability(row));
});
</script>
@endsection