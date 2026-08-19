@extends('layouts.app')

@section('title', 'Create Customer Account - StoreCraft')

@section('content')
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-6 col-lg-5">
            <!-- Register Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-user-plus fs-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Create Account</h4>
                        <p class="text-muted small">Join StoreCraft for seamless shopping</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-dark">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control bg-light border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g. John Smith" 
                                       required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="e.g. john@example.com" 
                                       required>
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
                                       placeholder="At least 6 characters" 
                                       required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock-keyhole"></i></span>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control bg-light border-start-0 ps-0" 
                                       placeholder="Re-enter password" 
                                       required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                            <i class="fa-solid fa-check me-2"></i> Register Account
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small text-muted">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
