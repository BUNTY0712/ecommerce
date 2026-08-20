@extends('layouts.app')

@section('title', 'Customer Login - StoreCraft')

@section('content')
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-6 col-lg-5">
            <!-- Store Brand Header -->
            <div class="text-center mb-4">
                <a class="brand-logo d-inline-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">
                    <i class="fa-solid fa-bag-shopping fs-2"></i>
                    <span class="fs-3 fw-bold text-dark">Store<span class="text-primary">Craft</span></span>
                </a>
            </div>

            <!-- Test Credentials Helper Box -->
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                    <i class="fa-solid fa-key text-info"></i> Demo Customer Account
                </div>
                <div class="small">
                    <strong>Email:</strong> <code>user@example.com</code><br>
                    <strong>Password:</strong> <code>password</code>
                </div>
            </div>

            <!-- Login Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-user-check fs-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Welcome Back!</h4>
                        <p class="text-muted small">Sign in to your customer account to continue</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email', 'user@example.com') }}" 
                                       required 
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
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

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                            <label class="form-check-label small text-muted" for="remember">
                                Remember me on this device
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small text-muted">
                        Don't have an account yet? 
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1">Register Now</a>
                    </div>
                </div>
            </div>

            <!-- Back to Store Link -->
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-1"></i> Return to Store
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
