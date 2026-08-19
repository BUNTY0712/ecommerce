@php
    $cartData = \App\Services\CartService::getValidatedCart();
    $cartCount = $cartData['item_count'];
    $cartTotal = $cartData['subtotal'];
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
            🎉 Exclusive Sale: Get 10% OFF with code <code>STORE10</code>
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
            <!-- Brand Logo -->
            <a class="brand-logo d-flex align-items-center gap-2 me-lg-4" href="{{ route('home') }}">
                <i class="fa-solid fa-bag-shopping fs-3"></i>
                <span>Store<span class="text-primary">Craft</span></span>
            </a>

            <!-- Header Quick Search Bar (Desktop) -->
            <div class="d-none d-lg-block flex-grow-1 mx-4" style="max-width: 480px;">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="input-group">
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

            <!-- Mobile Navbar Toggle -->
            <button class="navbar-toggler border-0 p-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Items & Cart Action -->
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

                <!-- Header Actions: Cart Button -->
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
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
