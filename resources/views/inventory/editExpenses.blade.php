@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h5 class="fw-bold h5">Edit Expense</h5>
        <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-right-from-bracket"></i> Back to Expenses
        </a>
    </div>

    <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 p-3" style="max-width: 600px; margin:auto;">
            <div class="row g-3 align-items-end">
                <!-- Date -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $expense->date->format('Y-m-d') }}"
                        required>
                </div>

                <!-- Category -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Rent" {{ $expense->category == 'Rent' ? 'selected' : '' }}>Rent</option>
                        <option value="Utilities" {{ $expense->category == 'Utilities' ? 'selected' : '' }}>Utilities
                        </option>
                        <option value="Supplies" {{ $expense->category == 'Supplies' ? 'selected' : '' }}>Supplies
                        </option>
                        <option value="Salaries" {{ $expense->category == 'Salaries' ? 'selected' : '' }}>Salaries
                        </option>
                        <option value="Others" {{ $expense->category == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>

                <!-- Amount -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Amount</label>
                    <input type="number" name="amount" class="form-control" placeholder="₱0.00" min="0"
                        value="{{ $expense->amount }}" required>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Details"
                        value="{{ $expense->description }}" required>
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="paid" {{ $expense->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $expense->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-floppy-disk"></i> Update Expense
                </button>
            </div>
        </div>
    </form>
</div>
@endsection