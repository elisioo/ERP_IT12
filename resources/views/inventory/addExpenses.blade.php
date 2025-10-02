@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h5 class="fw-bold h5">Add Multiple Expenses</h5>
        <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-right-from-bracket"></i> Back to Expenses
        </a>
    </div>

    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf

        <div id="expense-rows-wrapper">
            <!-- First Expense Row -->
            <div class="card shadow-sm border-0 mb-3 expense-row">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Date</label>
                            <input type="date" name="expenses[0][date]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Category</label>
                            <select name="expenses[0][category]" class="form-select" required>
                                <option>Rent</option>
                                <option>Utilities</option>
                                <option>Supplies</option>
                                <option>Salaries</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" name="expenses[0][description]" class="form-control"
                                placeholder="Details" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Amount</label>
                            <input type="number" name="expenses[0][amount]" class="form-control" placeholder="₱0.00"
                                min="0" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-bold">Status</label>
                            <select name="expenses[0][status]" class="form-select" required>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn remove-expense d-none text-danger">
                                <i class="fa-solid fa-square-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Another Expense Button -->
        <div class="mb-3">
            <button type="button" id="add-expense" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add Another Expense
            </button>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Save All Expenses
            </button>
        </div>
    </form>
</div>

<script>
let expenseIndex = 1;

document.getElementById('add-expense').addEventListener('click', function() {
    const wrapper = document.getElementById('expense-rows-wrapper');
    const firstRow = wrapper.querySelector('.expense-row');
    const newRow = firstRow.cloneNode(true);

    // Update input names and reset values
    newRow.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace(/\d+/, expenseIndex));
            input.value = input.tagName === 'SELECT' ? input.options[0].value : '';
        }
    });

    // Show remove button and bind click
    const removeBtn = newRow.querySelector('.remove-expense');
    removeBtn.classList.remove('d-none');
    removeBtn.addEventListener('click', function() {
        newRow.remove();
    });

    wrapper.appendChild(newRow);
    expenseIndex++;
});

// Ensure remove button works on first row if needed
document.querySelectorAll('.remove-expense').forEach(btn => {
    btn.addEventListener('click', function() {
        btn.closest('.expense-row').remove();
    });
});
</script>
@endsection