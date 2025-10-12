@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold h5">Menu</h5>
            <small class="text-muted mb-0">Browse and manage restaurant menu items</small>
        </div>
        <div>
            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="fa-solid fa-box-archive"></i> View Archived
            </a>
            <a href="{{ route('menus.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add New Item
            </a>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fa-solid fa-circle-check"></i> Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fa-solid fa-circle-exclamation"></i> Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <div class="row">
        <!-- Filters Column -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('menus.index') }}">
                        <!-- Search -->
                        <div class="mb-3 d-flex">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search menu..." value="{{ request('search') }}">
                            <button class="btn btn-sm btn-primary ms-2">Search</button>
                        </div>

                        <!-- Category -->
                        <h6 class="fw-bold mb-2">Category</h6>
                        <div class="mb-3">
                            @foreach($categories as $category)
                            <div>
                                <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}
                                    onchange="this.form.submit()"> <!-- auto submit -->
                                {{ $category->category_name }}
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Range -->
                        <h6 class="fw-bold mb-2">Price Range</h6>
                        <div class="mb-3">
                            <div>
                                <input type="radio" name="price" value="" {{ request('price')==''?'checked':'' }}
                                    onchange="this.form.submit()"> All
                            </div>
                            <div><input type="radio" name="price" value="100-200"
                                    {{ request('price')=='100-200'?'checked':'' }} onchange="this.form.submit()">
                                ₱100 -
                                ₱200</div>
                            <div><input type="radio" name="price" value="200-500"
                                    {{ request('price')=='200-500'?'checked':'' }} onchange="this.form.submit()">
                                ₱200 -
                                ₱500</div>
                            <div><input type="radio" name="price" value="500+"
                                    {{ request('price')=='500+'?'checked':'' }} onchange="this.form.submit()"> ₱500+
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Menu Items Column -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0 text-muted">Showing {{ $menus->firstItem() }}–{{ $menus->lastItem() }} of
                    {{ $menus->total() }} items</p>
                <select class="form-select form-select-sm w-auto" onchange="updateSort(this.value)">
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Sort by: Popular
                    </option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to
                        High
                    </option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High
                        to
                        Low</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
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
                                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-outline-dark"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <!-- Archived -->
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-url="{{ route('menus.destroy', $menu->id) }}"
                                    data-name="{{ $menu->menu_name }}">
                                    <i class="fa-solid fa-box-archive"></i>
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
                Are you sure you want to archive this item <strong id="menuName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Archive</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-box-archive"></i> Archived Menus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($archivedMenus->isEmpty())
                <p class="text-muted text-center">No archived menus found.</p>
                @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Menu Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivedMenus as $menu)
                        <tr>
                            <td>{{ $menu->menu_name }}</td>
                            <td>{{ $menu->category?->category_name ?? 'Uncategorized' }}</td>
                            <td>₱{{ number_format($menu->price, 2) }}</td>
                            <td class="text-center align-content-center">
                                <form action="{{ route('menus.restore', $menu->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-success btn-sm"><i
                                            class="fa-solid fa-rotate-left"></i> Restore</button>
                                </form>
                                <form action="{{ route('menus.forceDelete', $menu->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Permanently delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i>
                                        Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
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
        var modalTitle = deleteModal.querySelector('.modal-title');
        var submitButton = deleteModal.querySelector('button[type="submit"]');

        form.action = url;
        menuName.textContent = name;

        // Check if it's an archive (destroy) or force delete
        if (submitButton.textContent === 'Archive' || button.textContent.includes('Archive')) {
            modalTitle.textContent = 'Confirm Archive';
            submitButton.textContent = 'Archive';
            submitButton.classList.remove('btn-danger');
            submitButton.classList.add('btn-warning');
        } else {
            modalTitle.textContent = 'Confirm Delete';
            submitButton.textContent = 'Delete';
            submitButton.classList.remove('btn-warning');
            submitButton.classList.add('btn-danger');
        }
    });
});

function updateSort(sortValue) {
    const params = new URLSearchParams(window.location.search);
    params.set('sort', sortValue);
    window.location.search = params.toString();
}
</script>
@endsection