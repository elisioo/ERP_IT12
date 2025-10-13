@extends('layout.inventory_app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h5 class="fw-bold h5">Add Menu Items</h5>
        <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-right-from-bracket"></i> Back to Menu
        </a>
    </div>

    <!-- Add Menu Form -->
    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="menu-items-wrapper">
            <!-- One Menu Item Row -->
            <div class="card shadow-sm border-0 mb-3 menu-item">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="items[0][name]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Category</label>
                            <select name="items[0][category]" class="form-select" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Price (₱)</label>
                            <input type="number" step="0.01" name="items[0][price]" class="form-control" required>
                        </div>
                        <!-- Description -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" name="items[0][description]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Image</label>
                            <input type="file" name="items[0][image]" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn remove-item d-none text-danger">
                                <i class="fa-solid fa-square-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add More Button -->
        <div class="mb-3">
            <button type="button" id="add-item" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add Another Item
            </button>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Save All Items
            </button>
        </div>
    </form>
</div>

<!-- JS to duplicate menu item form -->
<script>
let itemIndex = 1;

document.getElementById('add-item').addEventListener('click', function() {
    const wrapper = document.getElementById('menu-items-wrapper');
    const firstItem = wrapper.querySelector('.menu-item');
    const newItem = firstItem.cloneNode(true);

    // Update inputs
    newItem.querySelectorAll('input, select').forEach(input => {
        let name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace(/\d+/, itemIndex));
            if (input.tagName === 'INPUT') input.value = '';
            if (input.tagName === 'SELECT') input.selectedIndex = 0;
        }
    });

    // Show remove button
    newItem.querySelector('.remove-item').classList.remove('d-none');

    // Append
    wrapper.appendChild(newItem);
    itemIndex++;
});

// Delegate remove button event
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.menu-item').remove();
    }
});
</script>
@endsection