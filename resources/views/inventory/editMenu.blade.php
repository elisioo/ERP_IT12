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

    <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 p-3" style="max-width: 400px; margin:auto;">
            <!-- Menu Image -->
            <div class="text-center mb-3">
                @if($menu->image)
                <img src="{{ asset('storage/'.$menu->image) }}" alt="Menu Image" class="img-fluid rounded"
                    style="height:200px; object-fit:cover; width:100%;">
                @else
                <div class="bg-light rounded"
                    style="height:200px; display:flex; align-items:center; justify-content:center;">
                    <span class="text-muted">No Image</span>
                </div>
                @endif
                <input type="file" name="image" class="form-control mt-2" accept="image/*">
            </div>

            <!-- Menu Name -->
            <div class="mb-3">
                <label class="form-label fw-bold">Menu Name</label>
                <input type="text" name="menu_name" class="form-control"
                    value="{{ old('menu_name', $menu->menu_name) }}" required>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label fw-bold">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label class="form-label fw-bold">Price (₱)</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $menu->price) }}" required>
            </div>

            <!-- Availability -->
            <div class="mb-3">
                <label class="form-label fw-bold">Available</label>
                <select name="is_available" class="form-select">
                    <option value="1" {{ $menu->is_available ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$menu->is_available ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-floppy-disk"></i> Update Menu
                </button>
            </div>
        </div>
    </form>
</div>
@endsection