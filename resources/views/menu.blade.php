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


    <style>
    .border {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .border:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        border-color: #ffffffff;
        background-color: #171717ff !important;
        /* Bootstrap primary color */
    }

    .border:hover i,
    .border:hover h5 {
        color: #ffffffff;
        /* Icon + text turns blue */
    }
    </style>

</head>

<body class="bg-light">
    <div class="container-fluid vh-100 d-flex flex-column">
        <!-- Topbar -->
        <div class="d-flex justify-content-end align-items-center p-3 border-bottom">
            <a href="" class="text-dark text-decoration-none">
                Logout <i class="fa-solid fa-right-from-bracket ms-1"></i>
            </a>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center">
            <!-- Logo and Welcome -->
            <div class="mb-4">
                <img src="{{ asset('img/kdr.png') }}" alt="Logo" style="width:120px; height:auto;">

                <!-- <i class='bx bxs-bowl-hot' style="font-size:4rem;"></i> -->
                <h2 class="fw-bold">Welcome!</h2>
                <p class="text-muted">
                    Your all-in-one ERP solution for <strong>employees</strong> and <strong>inventory</strong>.
                </p>
            </div>

            <!-- Menu Cards -->
            <div class="d-flex justify-content-center gap-4 mt-2">
                <!-- Employee -->
                <a href="#" class="text-decoration-none text-dark">
                    <div class="border border-dark rounded p-4 d-flex flex-column align-items-center justify-content-center shadow-sm"
                        style="width: 180px; height: 180px;">
                        <i class="fa-solid fa-id-badge fa-3x mb-3"></i>
                        <h5 class="mt-2">Employee</h5>
                    </div>
                </a>

                <!-- Inventory -->
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-dark">
                    <div class="border border-dark rounded p-4 d-flex flex-column align-items-center justify-content-center shadow-sm"
                        style="width: 180px; height: 180px;">
                        <i class="fa-solid fa-boxes-stacked fa-3x mb-3"></i>
                        <h5 class="mt-2">Inventory</h5>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>