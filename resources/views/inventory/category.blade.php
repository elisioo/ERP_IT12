@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold">Category Management</h5>
            <small class="text-muted">View, add, and explore all categories.</small>
        </div>
        <div class="d-flex gap-2">
            <!-- Go to Archive Button -->
            <a href="{{ route('categories.archived') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-box-archive"></i> View Archive
            </a>
            <!-- Add Category Button -->
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fa-solid fa-plus"></i> Add Category
            </button>
        </div>
    </div>


    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Category Cards -->
    <div class="row g-4">
        @forelse($categories as $category)
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                @if($category->image)
                <img src="{{ asset('storage/'.$category->image) }}" class="card-img-top"
                    alt="{{ $category->category_name }}" style="height: 160px; object-fit: cover;">
                @else
                <img src="https://via.placeholder.com/300x160?text=No+Image" class="card-img-top" alt="Placeholder">
                @endif
                <div class="card-body position-relative text-center">
                    <!-- Ellipsis Dropdown -->
                    <div class="dropdown position-absolute top-0 end-0 m-2">
                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal{{ $category->id }}">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                            </li>
                            <li>
                                <form action="{{ route('categories.archive', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Move to archive?');">
                                    @csrf
                                    <button class="dropdown-item text-warning" type="submit">
                                        <i class="fa-solid fa-box-archive"></i> Move to Archive
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <h6 class="fw-bold mt-3">{{ $category->category_name }}</h6>
                    <p class="text-muted small">{{ $category->description ?? 'No description' }}</p>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#categoryModal{{ $category->id }}">
                        View Items
                    </button>
                </div>

            </div>
        </div>
        <!-- Edit Category Modal -->
        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category_name" class="form-control"
                                    value="{{ $category->category_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="2">{{ $category->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                @if($category->image)
                                <img src="{{ asset('storage/'.$category->image) }}" class="mt-2 rounded" height="80"
                                    alt="Current image">
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category Modal -->
        <div class="modal fade" id="categoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $category->category_name }} — Items</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($category->menus->count() > 0)
                        <div class="list-group">
                            @foreach($category->menus as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">{{ $item->description ?? 'No description' }}</small>
                                </div>
                                <span class="badge bg-primary">₱{{ number_format($item->price, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted text-center">No items found in this category.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-muted">No categories available.</p>
        @endforelse
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection