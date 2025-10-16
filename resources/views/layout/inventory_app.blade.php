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
    .archived {
        color: #6c757d !important;
        border-color: #6c757d !important;
    }
    .archived:hover {
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
        overflow: hidden;
    }

    /* Sidebar styles */
    .bg-dark {
        height: 100vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #555 #222;
        transition: all 0.3s ease;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        width: 16.666667%; /* col-lg-2 equivalent */
    }
    
    @media (min-width: 768px) {
        .bg-dark {
            width: 25%; /* col-md-3 equivalent */
        }
    }
    
    @media (min-width: 992px) {
        .bg-dark {
            width: 16.666667%; /* col-lg-2 equivalent */
        }
    }

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

    /* Collapsed sidebar */
    .sidebar-collapsed {
        width: 80px !important;
        min-width: 80px !important;
    }

    .sidebar-collapsed .sidebar-text {
        display: none;
    }

    .sidebar-collapsed .dropdown-toggle strong {
        display: none;
    }

    .sidebar-collapsed hr {
        margin: 0.5rem 0;
    }

    /* Main content adjustment */
    #main-content {
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #f8f9fa;
        transition: margin-left 0.3s ease;
        margin-left: 16.666667%; /* Default: matches col-lg-2 */
        width: 83.333333%;
    }
    
    @media (min-width: 768px) {
        #main-content {
            margin-left: 25%; /* matches col-md-3 */
            width: 75%;
        }
    }
    
    @media (min-width: 992px) {
        #main-content {
            margin-left: 16.666667%; /* matches col-lg-2 */
            width: 83.333333%;
        }
    }

    .content-expanded {
        margin-left: 80px !important;
        width: calc(100% - 80px) !important;
    }

    /* Toggle button */
    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: calc(16.666667% - 20px); /* Positioned at edge of sidebar */
        z-index: 1001;
        background-color: #212529;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    @media (min-width: 768px) {
        .sidebar-toggle {
            left: calc(25% - 20px);
        }
    }
    
    @media (min-width: 992px) {
        .sidebar-toggle {
            left: calc(16.666667% - 20px);
        }
    }

    .sidebar-toggle:hover {
        background-color: #343a40;
    }

    .sidebar-toggle.collapsed {
        left: 60px;
    }

    /* Active state styling */
    .active {
        background-color: #ffffffff !important;
        color: #212529 !important;
        border-radius: 0.375rem !important;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .bg-dark {
            width: 250px !important;
            transform: translateX(-100%);
        }

        .bg-dark.show {
            transform: translateX(0);
        }

        .sidebar-toggle {
            left: 20px;
            transform: translateX(0);
        }

        .sidebar-toggle.collapsed {
            left: 20px;
        }

        #main-content {
            margin-left: 0 !important;
        }
    }
    </style>
</head>

<body>
    <script>
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

    <!-- Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Fixed Sidebar -->
            <div class="col-md-3 col-lg-2 bg-dark p-3 d-flex flex-column" id="sidebar">
                @include('layout.sidebar', ['active' => $page ?? request()->route('page') ?? 'dashboard'])
            </div>

            <!-- Scrollable Main Content -->
            <div class="px-md-4 py-4" id="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    @include('employee.modals.profile')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loadingScreen');

        if (!sessionStorage.getItem('loaderShown')) {
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
                sessionStorage.setItem('loaderShown', 'true');
            }, 1000);
        } else {
            loader.style.display = 'none';
        }
    });

    // Sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        // Remove any classes added by server-side rendering
        sidebar.classList.remove('sidebar-collapsed');
        mainContent.classList.remove('content-expanded');
        toggleBtn.classList.remove('collapsed');
        
        // Check if sidebar state is saved
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (isCollapsed) {
            sidebar.classList.add('sidebar-collapsed');
            mainContent.classList.add('content-expanded');
            toggleBtn.classList.add('collapsed');
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('content-expanded');
            toggleBtn.classList.toggle('collapsed');
            
            // Save state
            const collapsed = sidebar.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', collapsed);
        });
    });
    </script>
</body>

</html>