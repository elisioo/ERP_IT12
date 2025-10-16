<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Korean Diner Davao - Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
    
    <style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8f9fa;
        overflow: hidden;
    }

    /* Sidebar styles */
    .sidebar {
        height: 100vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #ccc #f8f9fa;
        transition: all 0.3s ease;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        width: 280px;
        background-color: white;
        border-right: 1px solid #dee2e6;
    }

    .sidebar::-webkit-scrollbar {
        width: 8px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 10px;
    }
    .sidebar::-webkit-scrollbar-track {
        background-color: #f8f9fa;
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

    .sidebar-collapsed .sidebar-header div > div {
        display: none;
    }

    /* Main content adjustment */
    .main-content {
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #f8f9fa;
        transition: margin-left 0.3s ease;
        margin-left: 280px;
        width: calc(100% - 280px);
    }

    .content-expanded {
        margin-left: 80px !important;
        width: calc(100% - 80px) !important;
    }

    /* Toggle button */
    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: 260px;
        z-index: 1001;
        background-color: white;
        color: #212529;
        border: 1px solid #dee2e6;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .sidebar-toggle:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .sidebar-toggle.collapsed {
        left: 60px;
    }

    /* Navigation links */
    .nav-link {
        display: flex;
        align-items: center;
        color: #495057;
        transition: all 0.2s;
    }

    .nav-link:hover {
        background-color: #f8f9fa;
        color: #212529;
    }

    .sidebar-collapsed .nav-link {
        justify-content: center;
    }

    .sidebar-collapsed .nav-link i {
        margin: 0 !important;
    }

    /* Active state */
    li.active .nav-link,
    .nav-link.active {
        background-color: #0d6efd !important;
        color: white !important;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .sidebar {
            width: 280px !important;
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-toggle {
            left: 20px;
            transform: translateX(0);
        }

        .sidebar-toggle.collapsed {
            left: 20px;
        }

        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
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

    <!-- Loading Screen -->
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

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3" id="sidebar">
        @include('layout.employee.sidebar')
    </div>

    <!-- Main Content -->
    <div class="main-content p-4" id="mainContent">
        @yield('content')
    </div>

    @include('employee.modals.settings')
    @include('employee.modals.profile')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Loading screen
        window.addEventListener('load', function() {
            const loader = document.getElementById('loadingScreen');

            if (!sessionStorage.getItem('loaderShown')) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                        sessionStorage.setItem('loaderShown', 'true');
                    }, 500);
                }, 1000);
            } else {
                loader.style.display = 'none';
            }
        });

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            // Remove any classes added by server-side rendering
            sidebar.classList.remove('sidebar-collapsed');
            mainContent.classList.remove('content-expanded');
            toggleBtn.classList.remove('collapsed');
            
            // Check if sidebar state is saved
            const isCollapsed = localStorage.getItem('employeeSidebarCollapsed') === 'true';
            
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
                localStorage.setItem('employeeSidebarCollapsed', collapsed);
            });

            // Initialize tooltips for collapsed sidebar
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @vite('resources/js/settings.js')
</body>
</html>