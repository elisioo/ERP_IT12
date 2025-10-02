@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="h5 fw-bold">Expenses Management</h5>
            <p class="text-muted mb-0">Track daily, weekly, and monthly expenses.</p>
        </div>
        <a href="{{ route('expenses.add') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Expense
        </a>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-9">
            <!-- Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Expenses (This Month)</h6>
                        <h4 class="fw-bold text-danger">₱{{ number_format($totalThisMonth ?? 0, 2) }}</h4>
                        <small class="text-muted">As of {{ \Carbon\Carbon::now()->format('M d, Y') }}</small>
                    </div>
                    <i class="fa-solid fa-receipt fa-2x text-danger"></i>
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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Upcoming Payments</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        @foreach($upcomingPayments as $payment)
                        <li class="list-group-item"><i class="{{ $payment['icon'] }} me-2"></i> {{ $payment['title'] }}
                            - {{ \Carbon\Carbon::parse($payment['date'])->format('M d') }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

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