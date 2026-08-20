@php
    $cartData = \App\Services\CartService::getValidatedCart();
    $cartCount = $cartData['item_count'];
    $cartTotal = $cartData['subtotal'];
    $siteName = \App\Models\Setting::get('site_name', 'StoreCraft');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $announcementText = \App\Models\Setting::get('announcement_text', '🎉 Exclusive Sale: Get 10% OFF with code STORE10');
@endphp

<!-- Top Announcement Bar -->
<div class="announcement-bar py-2 px-3 text-center border-bottom border-dark border-opacity-10">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-none d-md-flex align-items-center gap-3">
            <span><i class="fa-solid fa-truck-fast text-info me-1"></i> Free Shipping on orders &ge; ₹1,000</span>
            <span>&bull;</span>
            <span><i class="fa-solid fa-shield-check text-success me-1"></i> 100% Genuine Products</span>
        </div>
        <div class="mx-auto mx-md-0 fw-semibold">
            {!! $announcementText !!}
        </div>
        <div class="d-none d-lg-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="text-warning text-decoration-none fw-bold"><i class="fa-solid fa-gauge-high me-1"></i> Admin Panel</a>
            <span>&bull;</span>
            <a href="#" class="text-white-50 text-decoration-none hover-white">Help Center</a>
        </div>
    </div>
</div>

<!-- Main Sticky Site Header -->
<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <!-- Mobile Header Top Bar (Logo + Cart + Hamburger) -->
            <div class="d-flex align-items-center justify-content-between w-100 d-lg-none">
                <a class="brand-logo d-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">
                    @if($siteLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($siteLogo))
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="max-height: 34px; object-fit: contain;">
                    @else
                        <i class="fa-solid fa-bag-shopping fs-4 text-primary"></i>
                        <span class="fs-5 fw-bold text-dark">{{ $siteName }}</span>
                    @endif
                </a>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm position-relative d-flex align-items-center gap-1.5 px-2.5 py-1 shadow-xs">
                        <i class="fa-solid fa-cart-shopping fs-6"></i>
                        <span class="badge bg-danger rounded-pill px-1.5 py-0.5 fs-7">{{ $cartCount }}</span>
                    </a>
                    <button class="navbar-toggler border-0 p-1.5 shadow-none ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon" style="width: 1.25em; height: 1.25em;"></span>
                    </button>
                </div>
            </div>

            <!-- Desktop Logo (Visible only on Desktop lg+) -->
            <a class="brand-logo d-none d-lg-flex align-items-center gap-2 me-lg-4 text-decoration-none" href="{{ route('home') }}">
                @if($siteLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($siteLogo))
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="max-height: 42px; object-fit: contain;">
                @else
                    <i class="fa-solid fa-bag-shopping fs-3 text-primary"></i>
                    <span class="fs-4 fw-bold text-dark">{{ $siteName }}</span>
                @endif
            </a>

            <!-- Header Quick Search Bar (Desktop) -->
            <div class="d-none d-lg-block flex-grow-1 mx-4" style="max-width: 440px;">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="input-group shadow-xs">
                        <span class="input-group-text bg-light border-0 ps-3 text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control bg-light border-0 py-2 shadow-none" 
                               placeholder="Search electronics, fashion, accessories..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary px-3">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mobile Quick Search Bar (Below Top Bar on Mobile < lg) -->
            <div class="w-100 d-lg-none mt-2">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="input-group input-group-sm shadow-xs">
                        <span class="input-group-text bg-light border border-end-0 ps-2.5 text-muted">
                            <i class="fa-solid fa-magnifying-glass fs-7"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control bg-light border border-start-0 border-end-0 py-1.5 shadow-none fs-7" 
                               placeholder="Search products..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary px-3 py-1.5 fs-7 fw-bold text-nowrap">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Navigation Items, Auth & Cart -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            Explore Products
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link text-warning fw-bold" href="{{ route('admin.dashboard') }}">
                            <i class="fa-solid fa-gauge-high me-1"></i> Admin Panel
                        </a>
                    </li>
                </ul>

                <!-- Right Header Controls: User Authentication & Cart -->
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">

                    <!-- Customer User Account Section -->
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 fw-semibold px-3 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user text-primary fs-5"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li class="px-3 py-2 border-bottom">
                                    <span class="d-block fw-bold text-dark small">{{ Auth::user()->name }}</span>
                                    <span class="d-block text-muted small">{{ Auth::user()->email }}</span>
                                </li>
                                <li>
                                    <a class="dropdown-item fw-semibold py-2" href="{{ route('orders.myOrders') }}">
                                        <i class="fa-solid fa-box-open me-2 text-primary"></i> My Orders
                                    </a>
                                </li>
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-menu-item dropdown-item text-warning fw-semibold py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-gauge-high me-2"></i> Admin Panel
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2 fw-semibold">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light border fw-semibold px-3 py-2">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary fw-semibold px-3 py-2 d-none d-sm-inline-block">
                            Register
                        </a>
                    @endauth

                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative d-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                        <i class="fa-solid fa-cart-shopping fs-5"></i>
                        <div class="text-start d-none d-sm-block leading-tight">
                            <span class="d-block small text-muted font-monospace" style="font-size: 0.7rem; text-transform: uppercase;">Shopping Cart</span>
                            <span class="fw-bold text-dark fs-7">₹{{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <span class="badge bg-danger rounded-pill px-2 py-1 fs-7 shadow-sm ms-1">
                            {{ $cartCount }}
                        </span>
                    </a>

                </div>
            </div>
        </div>
    </nav>
</header>
