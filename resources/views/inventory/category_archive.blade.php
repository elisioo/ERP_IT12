@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold">Archived Categories</h5>
            <small class="text-muted">Manage categories that were moved to archive.</small>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($categories->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 100px;">Image</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="text-center" style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>
                        @if($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->category_name }}"
                            class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                        @else
                        <img src="https://via.placeholder.com/80x60?text=No+Image" class="img-thumbnail">
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $category->category_name }}</td>
                    <td>{{ $category->description ?? 'No description' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <form action="{{ route('categories.restore', $category->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm  py-0 px-1">
                                    <i class="fa-solid fa-rotate-left fa-xm" title="Restore"></i>
                                </button>
                            </form>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure you want to permanently delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-1">
                                    <i class="fa-solid fa-trash fa-xm" title="Delete"></i>
                                </button>

                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center text-muted">No archived categories found.</p>
    @endif
</div>
@endsection