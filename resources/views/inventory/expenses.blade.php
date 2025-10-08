@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="h5 fw-bold">Expenses Management</h5>
            <small class="text-muted mb-0">Track daily, weekly, and monthly expenses.</small>
        </div>
        <a href="{{ route('expenses.add') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Expense
        </a>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-9">
            <!-- Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <i class="fa-solid fa-receipt fa-2x text-danger"></i>
                        </div>
                        <div class="col-auto">
                            <h6 class="text-muted mb-1">Total Expenses (This Month)</h6>
                            <h4 class="fw-bold text-danger">₱{{ number_format($totalThisMonth ?? 0, 2) }}</h4>
                            <small class="text-muted">As of {{ \Carbon\Carbon::now()->format('M d, Y') }}</small>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Expense List -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Expense Records</span>
                    <!-- Ellipsis Dropdown for Bulk Actions -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="bulkActionsDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bulkActionsDropdown">
                            <li>
                                <a class="dropdown-item" href="#" id="bulkDeleteBtn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" disabled>Delete Selected</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <form id="bulkDeleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selectedIds">

                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $index => $expense)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="rowCheckbox" value="{{ $expense->id }}">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>₱{{ number_format($expense->amount, 2) }}</td>
                                    <td>
                                        @if($expense->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                        @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.edit', $expense->id) }}"
                                            class="btn btn-sm btn-outline-dark">Edit</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger singleDeleteBtn"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-url="{{ route('expenses.destroy', $expense->id) }}"
                                            data-name="{{ $expense->description ?? $expense->category }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No expenses found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-3">
            <!-- Expense Breakdown -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">Expense Categories</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        @foreach($categoryTotals as $category => $total)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $category }} <span class="badge bg-primary">₱{{ number_format($total, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Upcoming Payments -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Upcoming Payments</span>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#addPaymentModal">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if($upcomingPaymentsStatus->count() > 0)
                    <ul class="list-group list-group-flush small">
                        @foreach($upcomingPaymentsStatus as $payment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="{{ $payment->icon ?? 'fa-solid fa-calendar' }} me-2 text-primary"></i>
                                <span class="fw-bold">{{ $payment->title }}</span>
                                <small class="text-muted d-block">
                                    Due: {{ \Carbon\Carbon::parse($payment->date)->format('M d, Y') }}
                                </small>
                            </div>
                            <form action="{{ route('upcoming.markPaid', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted mb-0">No upcoming payments.</p>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Payment History</span>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#paymentHistoryCollapse" aria-expanded="false"
                        aria-controls="paymentHistoryCollapse">
                        <i class="fa-solid fa-clock-rotate-left"></i> View History
                    </button>
                </div>

                <div id="paymentHistoryCollapse" class="collapse">
                    <div class="card-body p-0">
                        <div id="paymentHistoryList" class="scrollable-history">
                            @if(count($paidPayments) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($paidPayments as $payment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i
                                            class="{{ $payment->icon ?? 'fa-solid fa-calendar-check' }} text-success me-2"></i>
                                        <span class="fw-semibold">{{ $payment->title }}</span>
                                        <small class="text-muted d-block">
                                            Paid on: {{ \Carbon\Carbon::parse($payment->updated_at)->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <form action="{{ route('upcoming.unmark', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-rotate-left"></i> Undo
                                        </button>
                                    </form>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-muted text-center py-3 mb-0">No payment history yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>
</div>

<!-- Add Upcoming Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('upcoming.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addPaymentModalLabel">Add Upcoming Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Static Payment Type Dropdown -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Payment Type</label>
                        <select name="title" class="form-select" required>
                            <option value="">-- Choose Payment --</option>
                            <option value="Electric Bill" data-icon="fa-solid fa-bolt">⚡ Electric Bill</option>
                            <option value="Water Bill" data-icon="fa-solid fa-faucet">🚰 Water Bill</option>
                            <option value="Internet Bill" data-icon="fa-solid fa-wifi">🌐 Internet Bill</option>
                            <option value="Rent" data-icon="fa-solid fa-house">🏠 Rent</option>
                            <option value="Tuition Fee" data-icon="fa-solid fa-graduation-cap">🎓 Tuition Fee</option>
                            <option value="Others" data-icon="fa-solid fa-coins">💰 Others</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Due Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <input type="hidden" name="icon" id="iconInput">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Automatically set the FontAwesome icon when the user selects a payment type
document.addEventListener('DOMContentLoaded', function() {
    const titleSelect = document.querySelector('select[name="title"]');
    const iconInput = document.getElementById('iconInput');

    titleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const icon = selectedOption.getAttribute('data-icon');
        iconInput.value = icon || 'fa-solid fa-calendar';
    });
});
</script>






<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="expenseName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger btn-sm" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- <script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.mark-paid-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams(new FormData(form))
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    // Remove from upcoming list
                    const item = document.getElementById(`payment-${result.id}`);
                    item.classList.add('fade-out');
                    setTimeout(() => item.remove(), 300);

                    // Optionally add to history list
                    const historyList = document.querySelector('#paymentHistoryList ul');
                    if (historyList) {
                        historyList.insertAdjacentHTML('afterbegin', `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fa-solid fa-calendar-check me-2 text-success"></i>
                                    <span class="fw-semibold">${item.querySelector('.fw-bold').innerText}</span>
                                    <small class="text-muted d-block">Paid: Today</small>
                                </div>
                            </li>
                        `);
                    }
                }
            }
        });
    });
});
</script> -->

<style>
.fade-out {
    opacity: 0;
    transition: opacity 0.3s ease-out;
}

.scrollable-history {
    max-height: 250px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cfcfcf transparent;
}

.scrollable-history::-webkit-scrollbar {
    width: 6px;
}

.scrollable-history::-webkit-scrollbar-thumb {
    background-color: #cfcfcf;
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    const expenseName = document.getElementById('expenseName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    let deleteUrl = '';

    // Handle individual delete buttons
    document.querySelectorAll('.singleDeleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteUrl = this.getAttribute('data-url');
            expenseName.textContent = this.getAttribute('data-name');
            confirmDeleteBtn.onclick = function() {
                window.location.href = deleteUrl;
            };
        });
    });

    // Handle Select All checkbox
    selectAll.addEventListener('change', function() {
        rowCheckboxes.forEach(cb => cb.checked = this.checked);
        bulkDeleteBtn.classList.toggle('disabled', !this.checked && ![...rowCheckboxes].some(cb => cb
            .checked));
    });

    // Enable bulk delete if any checkbox is checked
    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            bulkDeleteBtn.classList.toggle('disabled', ![...rowCheckboxes].some(c => c
                .checked));
        });
    });

    // Bulk delete button click
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedIds = [...rowCheckboxes].filter(cb => cb.checked).map(cb => cb.value);
        if (selectedIds.length === 0) return;
        expenseName.textContent = selectedIds.length + ' selected expense(s)';
        confirmDeleteBtn.onclick = function() {
            const idsInput = document.getElementById('selectedIds');
            idsInput.value = selectedIds.join(',');
            bulkDeleteForm.submit();
        };
    });
});
</script>
@endsection