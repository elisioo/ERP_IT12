@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Menu</h5>
            <small class="text-muted mb-0">Browse and manage restaurant menu items</small>
        </div>
        <a href="{{ route('menus.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add New Item
        </a>
    </div>

    <div class="row">
        <!-- Filters Column -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <!-- Search -->
                    <form class="mb-3 d-flex" method="GET" action="{{ route('menus.index') }}">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search menu..." value="{{ request('search') }}">
                        <button class="btn btn-sm btn-primary ms-2">Search</button>
                    </form>

                    <!-- Category -->
                    <h6 class="fw-bold mb-2">Category</h6>
                    <div class="mb-3">
                        @foreach($categories as $category)
                        <div>
                            <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}>
                            {{ $category->category_name }}
                        </div>
                        @endforeach
                    </div>

                    <!-- Meal Time -->
                    <h6 class="fw-bold mb-2">Meal Times</h6>
                    <div class="mb-3">
                        @foreach(['Breakfast','Lunch','Dinner','Snack'] as $meal)
                        <div>
                            <input type="checkbox" name="meal_time[]" value="{{ $meal }}"
                                {{ in_array($meal, request('meal_time', [])) ? 'checked' : '' }}>
                            {{ $meal }}
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Range -->
                    <h6 class="fw-bold mb-2">Price Range</h6>
                    <div class="mb-3">
                        <div><input type="radio" name="price" value="100-200"> ₱100 - ₱200</div>
                        <div><input type="radio" name="price" value="200-500"> ₱200 - ₱500</div>
                        <div><input type="radio" name="price" value="500+"> ₱500+</div>
                    </div>

                    <!-- Rating -->
                    <h6 class="fw-bold mb-2">Rating</h6>
                    <div class="mb-3">
                        <div><input type="radio" name="rating" value="5"> ★★★★★</div>
                        <div><input type="radio" name="rating" value="4"> ★★★★☆</div>
                        <div><input type="radio" name="rating" value="3"> ★★★☆☆</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items Column -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0 text-muted">Showing {{ $menus->firstItem() }}–{{ $menus->lastItem() }} of
                    {{ $menus->total() }} items</p>
                <select class="form-select form-select-sm w-auto" onchange="window.location.href=this.value;">
                    <option value="{{ route('menus.index', ['sort' => 'popular']) }}">Sort by: Popular</option>
                    <option value="{{ route('menus.index', ['sort' => 'price_asc']) }}">Price: Low to High</option>
                    <option value="{{ route('menus.index', ['sort' => 'price_desc']) }}">Price: High to Low</option>
                    <option value="{{ route('menus.index', ['sort' => 'newest']) }}">Newest</option>
                </select>
            </div>

            <div class="row">
                @forelse($menus as $menu)
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://via.placeholder.com/300x150?text=No+Image' }}"
                            class="card-img-top" style="height:150px;object-fit:cover;">
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1">{{ $menu->menu_name }}</h6>
                            <small
                                class="text-muted d-block">{{ $menu->category?->category_name ?? 'Uncategorized' }}</small>
                            <!-- <div class="mb-2 text-warning">
                                @for($i=1; $i<=5; $i++) @if($i <=floor($menu->rating)) ★
                                    @elseif($i == ceil($menu->rating) && $menu->rating != floor($menu->rating)) ☆
                                    @else ☆
                                    @endif
                                    @endfor
                                    ({{ number_format($menu->rating,1) }})
                            </div> -->
                            <h6 class="fw-bold text-primary mb-2">₱{{ number_format($menu->price, 2) }}</h6>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-dark">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-url="{{ route('menus.destroy', $menu->id) }}"
                                    data-name="{{ $menu->menu_name }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">No menu items found.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $menus->links('pagination::bootstrap-5') }}
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
                Are you sure you want to delete <strong id="menuName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS to handle delete modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var url = button.getAttribute('data-url');
        var name = button.getAttribute('data-name');
        var form = deleteModal.querySelector('#deleteForm');
        var menuName = deleteModal.querySelector('#menuName');

        form.action = url;
        menuName.textContent = name;
    });
});
</script>
@endsection