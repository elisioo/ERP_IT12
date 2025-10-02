@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h5 class="fw-bold h5">Edit Menu Item</h5>
        <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-right-from-bracket"></i> Back to Menu
        </a>
    </div>

    <!-- Edit Menu Form -->
    <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <!-- Menu Name -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="menu_name" class="form-control"
                            value="{{ old('menu_name', $menu->menu_name) }}" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Price (₱)</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ old('price', $menu->price) }}" required>
                    </div>

                    <!-- Availability -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Available</label>
                        <select name="is_available" class="form-select">
                            <option value="1" {{ $menu->is_available ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$menu->is_available ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Image -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($menu->image)
                        <img src="{{ asset('storage/'.$menu->image) }}" alt="Menu Image" class="img-thumbnail mt-2"
                            style="height:100px;object-fit:cover;">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Update Menu
            </button>
        </div>
    </form>
</div>
@endsection