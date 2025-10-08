<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Korean Diner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="bg-light">
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow d-flex flex-row overflow-hidden login-card" style="width: 800px; height: 500px;">
            <!-- Left side with image -->
            <div class="col-6 position-relative image-side">
                <img src="{{ asset('img/login_photo.jpg') }}" alt="Korean Restaurant" 
                     class="w-100 h-100" style="object-fit: cover; filter: blur(1px);">
                <div class="position-absolute top-0 start-0 w-100 h-100" 
                     style="background: rgba(0,0,0,0.2);"></div>
            </div>
            
            <!-- Right side with login form -->
            <div class="col-6 p-5 d-flex flex-column justify-content-center form-side">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/kdr.png') }}" alt="Logo" class="logo-pop" style="width: 80px; height: 80px;">
                    <h3 class="mt-3">Korean Diner Davao</h3>
                    <p class="text-muted">Admin Login</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-decoration-none">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
