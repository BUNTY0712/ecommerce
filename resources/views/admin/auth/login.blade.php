@php
    $siteName = \App\Models\Setting::get('site_name', 'StoreCraft');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $primaryColor = \App\Models\Setting::get('theme_primary_color', '#4f46e5');
    $secondaryColor = \App\Models\Setting::get('theme_secondary_color', '#7c3aed');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ $siteName }} Control Panel</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-login-card {
            background-color: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .admin-login-header {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
            color: #ffffff;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
        }

        .text-primary {
            color: {{ $primaryColor }} !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%) !important;
            border: none !important;
            box-shadow: 0 4px 14px {{ $primaryColor }}40 !important;
        }
        
        .btn-primary:hover {
            opacity: 0.94;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <!-- Admin Test Account Helper -->
            <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4" style="background-color: #fffbeb; color: #92400e;">
                <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                    <i class="fa-solid fa-user-shield"></i> Demo Administrator Account
                </div>
                <div class="small">
                    <strong>Email:</strong> <code>admin@example.com</code><br>
                    <strong>Password:</strong> <code>password</code>
                </div>
            </div>

            <!-- Login Card -->
            <div class="admin-login-card">
                <div class="admin-login-header text-center">
                    <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm p-2" style="width: 68px; height: 68px;">
                        @if($siteLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($siteLogo))
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="max-height: 44px; max-width: 44px; object-fit: contain;">
                        @else
                            <i class="fa-solid fa-shield-halved fs-2" style="color: {{ $primaryColor }};"></i>
                        @endif
                    </div>
                    <h3 class="fw-bold mb-1">{{ $siteName }} Admin</h3>
                    <p class="mb-0 text-white-50 small">Control Panel Authentication</p>
                </div>

                <div class="p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 py-2 px-3 small mb-4" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 py-2 px-3 small mb-4" role="alert">
                            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.login') }}" method="POST">
                        @csrf

                        <!-- Admin Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">Admin Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user-gear"></i></span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email', 'admin@example.com') }}" 
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Admin Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-key"></i></span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control bg-light border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                       value="password" 
                                       required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                            <i class="fa-solid fa-lock-open me-2"></i> Access Admin Panel
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
                            <i class="fa-solid fa-arrow-left me-1"></i> Return to Public Store
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
