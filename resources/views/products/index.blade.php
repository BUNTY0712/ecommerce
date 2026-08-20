@extends('layouts.app')

@section('title', 'Explore Premium Products - StoreCraft')

@section('content')
<div class="container">

    <!-- Hero Showcase Banner -->
    <div id="hero-banner" class="hero-banner rounded-4 p-4 p-md-5 mb-5 text-white position-relative overflow-hidden shadow-sm" 
         style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
        
        <!-- Background Overlay Shapes -->
        <div class="position-absolute top-0 end-0 p-5 opacity-10 pointer-events-none d-none d-md-block">
            <i class="fa-solid fa-bag-shopping" style="font-size: 16rem; margin-top: -30px; margin-right: -40px;"></i>
        </div>

        <div class="row align-items-center position-relative z-1">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill text-uppercase mb-3 shadow-sm" style="letter-spacing: 0.05em;">
                    <i class="fa-solid fa-sparkles me-1"></i> New Season Arrivals 2026
                </span>
                <h1 class="fw-extrabold display-5 mb-3 text-white">
                    Elevate Your Lifestyle with Premium Goods
                </h1>
                <p class="lead text-white-50 mb-4" style="max-width: 600px;">
                    Explore curated collections of top-rated electronics, fashion apparel, home essentials, and lifestyle accessories at unbeatable prices.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#products-grid" class="btn btn-primary btn-lg px-4 py-3 fw-bold">
                        <i class="fa-solid fa-grid-2 me-2"></i> Shop All Products
                    </a>
                    <a href="{{ route('products.index', ['category' => 1]) }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                        <i class="fa-solid fa-bolt me-2"></i> Electronics Sale
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Features Bar -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="feature-icon-box flex-shrink-0">
                        <i class="fa-solid fa-truck-fast text-primary"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 fs-7">Free Delivery</h6>
                        <span class="small text-muted">On orders &ge; ₹1,000</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="feature-icon-box flex-shrink-0" style="background: #ecfdf5; color: #10b981;">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 fs-7">Secure Payment</h6>
                        <span class="small text-muted">Razorpay 256-bit SSL</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="feature-icon-box flex-shrink-0" style="background: #fef3c7; color: #d97706;">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 fs-7">Easy Returns</h6>
                        <span class="small text-muted">7 days hassle-free</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="feature-icon-box flex-shrink-0" style="background: #f3e8ff; color: #9333ea;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0 fs-7">24/7 Support</h6>
                        <span class="small text-muted">Dedicated assistance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3" id="products-grid">
        <div>
            <h3 class="fw-bold text-dark mb-1">Our Products Catalog</h3>
            <p class="text-muted small mb-0">Showing {{ $products->total() }} items available for immediate dispatch</p>
        </div>

        <!-- Search Input -->
        <div style="min-width: 280px;">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0 ps-0 bg-white" 
                           placeholder="Filter catalog..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Filter Pills -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('products.index', array_filter(['search' => request('search')])) }}" 
           class="btn {{ empty(request('category')) ? 'btn-primary shadow-sm' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold">
            <i class="fa-solid fa-layer-group me-1"></i> All Categories
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('products.index', array_filter(['category' => $cat->id, 'search' => request('search')])) }}" 
               class="btn {{ (string)request('category') === (string)$cat->id ? 'btn-primary shadow-sm' : 'btn-white border text-dark' }} rounded-pill px-4 py-2 fw-semibold">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Filter Active Badge Indicator -->
    @if(!empty(request('search')) || !empty(request('category')))
        <div class="alert alert-light border shadow-sm rounded-3 py-2 px-3 mb-4 d-flex align-items-center justify-content-between">
            <div class="small">
                <i class="fa-solid fa-filter me-2 text-primary"></i> Active Filters:
                @if(!empty(request('search')))
                    <span class="badge bg-secondary me-2">Search: "{{ request('search') }}"</span>
                @endif
                @if(!empty(request('category')))
                    <span class="badge bg-primary me-2">Category: {{ optional($categories->firstWhere('id', request('category')))->name }}</span>
                @endif
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-link text-danger text-decoration-none fw-semibold">
                <i class="fa-solid fa-xmark me-1"></i> Clear All
            </a>
        </div>
    @endif

    <!-- Product Grid -->
    @if($products->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($products as $product)
                <div class="col">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center my-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4 bg-white">
            <div class="card-body">
                <div class="mb-3 text-muted">
                    <i class="fa-solid fa-boxes-packing fs-1 text-primary opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No Matching Products Found</h4>
                <p class="text-muted small mb-4" style="max-width: 420px; margin: 0 auto;">
                    We couldn't find any products matching your active filters. Try searching for a different keyword or category.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="fa-solid fa-rotate-left me-2"></i> Reset Filters
                </a>
            </div>
        </div>
    @endif

</div>

@push('styles')
<style>
    .hero-banner {
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease, margin 0.4s ease, padding 0.4s ease, transform 0.4s ease;
        max-height: 800px;
        opacity: 1;
        transform: translateY(0);
    }
    .hero-banner.hero-hidden {
        max-height: 0;
        opacity: 0;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        transform: translateY(-15px);
        pointer-events: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const heroBanner = document.getElementById('hero-banner');
        if (!heroBanner) return;

        function handleScroll() {
            if (window.scrollY > 40) {
                heroBanner.classList.add('hero-hidden');
            } else {
                heroBanner.classList.remove('hero-hidden');
            }
        }

        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();
    });
</script>
@endpush
@endsection
