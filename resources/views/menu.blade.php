<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP Menu</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/kdr.png') }}" type="image/x-icon">

    <!-- Google Font (Korean Diner Style) -->
    <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&family=Noto+Sans+KR:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
    /* 🌄 Background Image */
    body {
        background: url('{{ asset("img/menu_bg.png") }}') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        font-family: 'Do Hyeon', 'Noto Sans KR', sans-serif;
    }

    /* Overlay */
    .overlay {
        background-color: rgba(0, 0, 0, 0.404);
        min-height: 100vh;
    }

    /* White text default */
    h1,
    h5,
    p,
    a,
    button {
        color: #f0f0f0 !important;
    }

    /* Logout button */
    .btn-link {
        color: #f0f0f0 !important;
    }

    .btn-link:hover {
        color: #f8d7da !important;
    }

    /* Logo shadow */
    img.logo {
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
    }

    /* Card Style */
    .menu-card {
        width: 220px;
        border: 2px solid #fff;
        border-radius: 1rem;
        overflow: hidden;
        color: #202020;
        background-color: rgb(235, 230, 230);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        cursor: pointer;
    }

    .menu-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        background-color: #fff;
    }

    .menu-card:hover h5,
    .menu-card:hover i {
        color: #000 !important;
    }

    .menu-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .menu-card-body {
        padding: 1rem;
    }

    .custom-text-shadow {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        /* horizontal-offset vertical-offset blur-radius color */
    }

    .menu-card-body i {
        margin-bottom: 8px;
    }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="container-fluid vh-100 d-flex flex-column">
            <!-- Topbar -->
            <div class="d-flex justify-content-end align-items-center p-3">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none p-0">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </div>

            <!-- Main content -->
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center">
                <!-- Logo and Welcome -->
                <div class="mb-4">
                    <img src="{{ asset('img/kdr.png') }}" alt="Logo" class="logo" style="width:150px; height:auto;">
                    <h1 class="h1 mb-1 custom-text-shadow" style="font-size: 70px;">Korean Diner Davao</h1>
                    <p class="custom-text-shadow" style="font-size: 1.2rem; color: #589bff;">
                        Your all-in-one ERP solution for employees and inventory.
                    </p>
                </div>

                <!-- Menu Cards -->
                <div class="d-flex justify-content-center gap-4 mt-2 flex-wrap">

                    <!-- Employee Card -->
                    <a href="{{ route('employee.dashboard') }}" class="text-decoration-none">
                        <div class="menu-card">
                            <img src="{{ asset('img/crew.jpg') }}" alt="Employee Photo">
                            <div class="menu-card-body text-center d-flex flex-column align-items-center">
                                <div class="row">
                                    <div class="col">
                                        {{-- <i class="fa-solid fa-id-badge fa-2x text-dark"></i> --}}
                                        {{-- </div>
                                  <div class="col"> --}}
                                        <h5 class="mt-2 text-dark">Employee</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Inventory Card -->
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">
                        <div class="menu-card">
                            <img src="{{ asset('img/korean_food.jpg') }}" alt="Korean Food Photo">
                            <div class="menu-card-body text-center">
                                {{-- <i class="fa-solid fa-boxes-stacked fa-2x"></i> --}}
                                <h5 class="mt-2 text-dark">Inventory</h5>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>