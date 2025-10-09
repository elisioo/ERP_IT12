@if($orders->isEmpty())
<p class="text-muted">No archived orders found.</p>
@else
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Archived At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
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
                    <span class="text-muted">{{ $order->deleted_at->format('M d, Y h:i A') }}</span>
                </td>
                <td>
                    <!-- Restore Button -->
                    <form action="{{ route('orders.restore', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa-solid fa-rotate-left"></i> Restore
                        </button>
                    </form>

                    <!-- Update Archive Date -->
                    <button class="btn btn-sm btn-outline-secondary update-date-btn" data-id="{{ $order->id }}">
                        <i class="fa-solid fa-calendar-pen"></i>
                    </button>
                </td>
            </tr>
            <!-- Hidden Date Update Row -->
            <tr id="updateRow-{{ $order->id }}" class="d-none">
                <td colspan="6" class="bg-light">
                    <form class="d-flex align-items-center justify-content-between updateArchiveDateForm"
                        data-id="{{ $order->id }}">
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-bold small mb-0">New Archive Date:</label>
                            <input type="datetime-local" name="deleted_at" class="form-control form-control-sm"
                                value="{{ $order->deleted_at->format('Y-m-d\TH:i') }}">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <button type="button" class="btn btn-sm btn-light cancel-update"
                                data-id="{{ $order->id }}">Cancel</button>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $orders->links() }}
</div>
@endif

<!-- Inline JS for Archive Date Update -->
<script>
document.querySelectorAll('.update-date-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('updateRow-' + id).classList.remove('d-none');
    });
});

document.querySelectorAll('.cancel-update').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('updateRow-' + id).classList.add('d-none');
    });
});

document.querySelectorAll('.updateArchiveDateForm').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const data = new FormData(this);

        fetch(`/orders/${id}/update-archive-date`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: data
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Archive date updated successfully!');
                    document.getElementById('updateRow-' + id).classList.add('d-none');
                } else {
                    alert('Failed to update archive date.');
                }
            })
            .catch(() => alert('Error updating archive date.'));
    });
});
</script>