@extends('layout.inventory_app')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Order ID <span class="text-primary">#ORD-1001</span></h5>
            <p class="text-muted mb-0">Orders > <span class="text-danger">Order Details</span></p>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2">On Process</span>
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
                            <tr>
                                <td>Bulgogi Beef</td>
                                <td>1</td>
                                <td>Extra sauce</td>
                                <td>₱480.00</td>
                                <td>₱480.00</td>
                            </tr>
                            <tr>
                                <td>Bibimbap</td>
                                <td>2</td>
                                <td>No egg</td>
                                <td>₱250.00</td>
                                <td>₱500.00</td>
                            </tr>
                            <tr>
                                <td>Kimchi</td>
                                <td>1</td>
                                <td>Spicy</td>
                                <td>₱150.00</td>
                                <td>₱150.00</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end mt-3">
                        <h6 class="fw-bold">Total Amount: ₱1,130.00</h6>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Customer</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> Maria Santos</p>
                    <p class="mb-1"><strong>Service Type:</strong> Dine-in (Table 5)</p>
                    <p class="mb-1"><strong>Email:</strong> maria.santos@example.com</p>
                    <p class="mb-0"><strong>Phone:</strong> 0912-345-6789</p>
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
                            <span class="ms-2">Order Placed - 10:15 AM</span>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                            <span class="ms-2">Order Confirmed - 10:30 AM</span>
                        </li>
                        <li class="mb-3">
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i></span>
                            <span class="ms-2">On Process (Preparing Food)</span>
                        </li>
                        <li>
                            <span class="badge bg-secondary"><i class="fa-solid fa-hourglass-half"></i></span>
                            <span class="ms-2">Completed (Ready for Pick-up / Served)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Status Update Buttons -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Update Status</div>
                <div class="card-body">
                    <form method="POST" action="#">
                        @csrf
                        @method('PUT')
                        <div class="d-grid gap-2">
                            <button type="submit" name="status" value="on_process" class="btn btn-warning">Mark as On
                                Process</button>
                            <button type="submit" name="status" value="completed" class="btn btn-success">Mark as
                                Completed</button>
                            <button type="submit" name="status" value="canceled" class="btn btn-danger">Cancel
                                Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection