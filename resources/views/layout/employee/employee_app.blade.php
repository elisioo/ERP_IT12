<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional custom CSS -->
    <style>
        body {
            min-height: 100vh;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            min-height: 100vh;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }
        .sidebar a.active, .sidebar a:hover {
            color: #fff;
            background: #495057;
        }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        @include('layout.employee.sidebar')
    </div>

    <div class="flex-fill p-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
