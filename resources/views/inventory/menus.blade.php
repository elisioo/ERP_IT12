@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Menu</h5>
            <p class="text-muted mb-0">Browse and manage restaurant menu items</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm">+ Add New Item</a>
    </div>

    <div class="row">
        <!-- Filters Column -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <!-- Search -->
                    <form class="mb-3 d-flex">
                        <input type="text" class="form-control form-control-sm" placeholder="Search menu...">
                        <button class="btn btn-sm btn-primary ms-2">Search</button>
                    </form>

                    <!-- Category -->
                    <h6 class="fw-bold mb-2">Category</h6>
                    <div class="mb-3">
                        <div><input type="checkbox" checked> Chicken</div>
                        <div><input type="checkbox" checked> Beef</div>
                        <div><input type="checkbox" checked> Pasta</div>
                        <div><input type="checkbox"> Seafood</div>
                        <div><input type="checkbox"> Pizza</div>
                        <div><input type="checkbox"> Burgers</div>
                        <div><input type="checkbox"> Desserts</div>
                        <div><input type="checkbox"> Beverages</div>
                    </div>

                    <!-- Meal Time -->
                    <h6 class="fw-bold mb-2">Meal Times</h6>
                    <div class="mb-3">
                        <div><input type="checkbox" checked> Breakfast</div>
                        <div><input type="checkbox"> Lunch</div>
                        <div><input type="checkbox"> Dinner</div>
                        <div><input type="checkbox"> Snack</div>
                    </div>

                    <!-- Price Range -->
                    <h6 class="fw-bold mb-2">Price Range</h6>
                    <div class="mb-3">
                        <div><input type="radio" name="price"> ₱100 - ₱200</div>
                        <div><input type="radio" name="price"> ₱200 - ₱500</div>
                        <div><input type="radio" name="price" checked> ₱500+</div>
                    </div>

                    <!-- Rating -->
                    <h6 class="fw-bold mb-2">Rating</h6>
                    <div class="mb-3">
                        <div><input type="radio" name="rating"> ★★★★★</div>
                        <div><input type="radio" name="rating"> ★★★★☆ & up</div>
                        <div><input type="radio" name="rating"> ★★★☆☆ & up</div>
                    </div>

                    <!-- Promos -->
                    <h6 class="fw-bold mb-2">Promos</h6>
                    <div>
                        <button class="btn btn-sm btn-outline-primary mb-2">Buy 1 Get 1 Free</button>
                        <button class="btn btn-sm btn-outline-primary mb-2">Seasonal Offer</button>
                        <button class="btn btn-sm btn-outline-primary mb-2">10% Off</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items Column -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0 text-muted">Showing 1–8 of 56 items</p>
                <select class="form-select form-select-sm w-auto">
                    <option>Sort by: Popular</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Newest</option>
                </select>
            </div>

            <div class="row">
                <!-- Example Card -->
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="/images/pizza.jpg" class="card-img-top" style="height:150px;object-fit:cover;">
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1">Smokey Supreme Pizza</h6>
                            <small class="text-muted d-block">Pizza</small>
                            <div class="mb-2 text-warning">★★★★☆ (4.5)</div>
                            <h6 class="fw-bold text-primary mb-2">₱650.00</h6>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm btn-dark">Edit</a>
                                <form action="#" method="POST" onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-dark">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Another Example -->
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="/images/salmon.jpg" class="card-img-top" style="height:150px;object-fit:cover;">
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1">Grilled Salmon</h6>
                            <small class="text-muted d-block">Seafood</small>
                            <div class="mb-2 text-warning">★★★★★ (4.7)</div>
                            <h6 class="fw-bold text-primary mb-2">₱1,200.00</h6>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm btn-dark">Edit</a>
                                <form action="#" method="POST" onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-dark">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More items here... -->
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled"><a class="page-link">&laquo;</a></li>
                        <li class="page-item active"><a class="page-link">1</a></li>
                        <li class="page-item"><a class="page-link">2</a></li>
                        <li class="page-item"><a class="page-link">3</a></li>
                        <li class="page-item"><a class="page-link">&raquo;</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection