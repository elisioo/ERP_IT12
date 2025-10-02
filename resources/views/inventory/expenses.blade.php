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
                <div class="card-header bg-white fw-bold">Expense Records</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
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
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No expenses found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
@endsection