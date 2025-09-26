<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 bg-dark p-3 vh-100 d-flex flex-column">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-4"><i class="fa-solid fa-bowl-food"></i> Inventory</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">Dashboard</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">Orders</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">Products</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">Customers</a>
                    </li>
                </ul>
                <hr>

                <!-- Dropdown at bottom -->
                <div class="dropdown mt-auto">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>Admin</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            </div>


            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-md-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                    <h1 class="h2">Dashboard</h1>
                    <a href="#" class="btn btn-primary">+ Add Item</a>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-bg-light mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <p class="card-text fs-3">120</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Low Stock</h5>
                                <p class="card-text fs-3">15</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Out of Stock</h5>
                                <p class="card-text fs-3">8</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="card">
                    <div class="card-header">Inventory List</div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Coca-Cola 1L</td>
                                    <td>Beverages</td>
                                    <td class="text-success">50</td>
                                    <td>₱45.00</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-dark">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-dark">Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Lucky Me Pancit Canton</td>
                                    <td>Noodles</td>
                                    <td class="text-warning">4</td>
                                    <td>₱12.00</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-dark">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-dark">Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Surf Powder 1kg</td>
                                    <td>Detergent</td>
                                    <td class="text-danger">0</td>
                                    <td>₱95.00</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-dark">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-dark">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>