<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventory</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="{{ asset('img/kdr.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8f9fa;
    }
    .archived
    {
        color: #6c757d !important;
        border-color: #6c757d !important;
    }
    .archived:hover
    {
        background-color: #fcfcfc !important;
        color: #272727 !important;
        border-color: #272727 !important;
    }

    .card-header i {
        font-size: 1rem;
    }
    .card {
        border-radius: 12px;
        }
    html, body {
        height: 100%;
        overflow: hidden; /* prevent double scrollbars */
    }

    /* Make only main content scrollable */
    #main-content {
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #f8f9fa;
    }

    /* Sidebar always fixed and full height */
    .bg-dark {
        height: 100vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #555 #222;
    }

    /* Optional: Custom scrollbar for sidebar */
    .bg-dark::-webkit-scrollbar {
        width: 8px;
    }
    .bg-dark::-webkit-scrollbar-thumb {
        background-color: #555;
        border-radius: 10px;
    }
    .bg-dark::-webkit-scrollbar-track {
        background-color: #222;
    }

    </style>



</head>

<body>
    <script>
    // If already loaded before, hide immediately
    if (sessionStorage.getItem('loaderShown')) {
        document.write('<style>#loadingScreen { display: none !important; }</style>');
    }
    </script>
    <div id="loadingScreen" class="loading-screen">
        <div class="loading-content">
            <img src="{{ asset('img/kdr.png') }}" alt="Korean Diner Logo" class="loading-logo">
            <div class="loading-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Fixed Sidebar -->
            <div class="col-md-3 col-lg-2 bg-dark p-3 d-flex flex-column position-fixed top-0 start-0 vh-100">
                @include('layout.sidebar', ['active' => $page ?? request()->route('page') ?? 'dashboard'])
            </div>

            <!-- Scrollable Main Content -->
            <div class="col-md-9 col-lg-10 ms-auto px-md-4 py-4" id="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    @include('employee.modals.profile')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loadingScreen');

        // Check if the loader was already shown in this browser tab
        if (!sessionStorage.getItem('loaderShown')) {
            // Show loader the first time
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
                // Mark as shown
                sessionStorage.setItem('loaderShown', 'true');
            }, 1000); // You can adjust this delay
        } else {
            // Skip loader for next pages
            loader.style.display = 'none';
        }
    });
    </script>

</body>


</html>