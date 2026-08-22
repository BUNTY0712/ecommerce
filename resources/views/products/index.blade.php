@extends('layouts.app')

@section('title', 'Explore Premium Products - StoreCraft')

@section('content')
<div class="container px-3 px-sm-4">

@php
    $activeBanners = \Illuminate\Support\Facades\DB::table('banners')
        ->where('status', 1)
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'desc')
        ->get();
@endphp

    <!-- Hero Showcase Carousel Slider -->
    @if(isset($activeBanners) && $activeBanners->count() > 0)
        <div id="heroBannerCarousel" class="carousel slide carousel-fade mb-4 mb-md-5 rounded-4 overflow-hidden shadow-md" data-bs-ride="carousel" data-bs-interval="5000">
            <!-- Carousel Indicators -->
            @if($activeBanners->count() > 1)
                <div class="carousel-indicators mb-3">
                    @foreach($activeBanners as $index => $banner)
                        <button type="button" 
                                data-bs-target="#heroBannerCarousel" 
                                data-bs-slide-to="{{ $index }}" 
                                class="{{ $loop->first ? 'active' : '' }}" 
                                aria-current="{{ $loop->first ? 'true' : 'false' }}" 
                                aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <!-- Carousel Slides -->
            <div class="carousel-inner">
                @foreach($activeBanners as $index => $banner)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }} position-relative" style="max-height: 480px; min-height: 360px; background-color: #0f172a;">
                        @if(!empty($banner->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($banner->image))
                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                 class="d-block w-100 object-fit-cover position-absolute top-0 start-0 h-100" 
                                 alt="{{ $banner->title ?? 'Banner Slide' }}"
                                 style="object-position: center;">
                        @else
                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1920&auto=format&fit=crop" 
                                 class="d-block w-100 object-fit-cover position-absolute top-0 start-0 h-100" 
                                 alt="Default Banner">
                        @endif

                        <!-- Gradient Glass Overlay for legible overlay text -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" 
                             style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.55) 60%, rgba(15, 23, 42, 0.25) 100%);"></div>

                        <!-- Content Overlay -->
                        <div class="container position-relative z-1 h-100 d-flex align-items-center p-4 p-sm-5 text-white" style="min-height: 360px;">
                            <div class="row align-items-center w-100">
                                <div class="col-lg-9 col-xl-8">
                                    @if($banner->badge_text)
                                        <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill text-uppercase mb-3 shadow-sm" style="letter-spacing: 0.05em; font-size: 0.75rem;">
                                            <i class="fa-solid fa-sparkles me-1"></i> {{ $banner->badge_text }}
                                        </span>
                                    @endif

                                    @if($banner->title)
                                        <h1 class="fw-extrabold display-6 display-md-5 mb-3 text-white">
                                            {{ $banner->title }}
                                        </h1>
                                    @endif

                                    @if($banner->subtitle)
                                        <p class="lead text-white-50 mb-4 fs-6 fs-md-5" style="max-width: 620px;">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif

                                    <div class="d-flex flex-wrap gap-2 gap-sm-3">
                                        @if($banner->button_text)
                                            <a href="{{ $banner->button_url ?: '#products-grid' }}" class="btn btn-primary px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center">
                                                {{ $banner->button_text }} <i class="fa-solid fa-arrow-right ms-2"></i>
                                            </a>
                                        @else
                                            <a href="#products-grid" class="btn btn-primary px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center">
                                                <i class="fa-solid fa-bag-shopping me-2"></i> Shop All Products
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            @if($activeBanners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon rounded-circle bg-dark bg-opacity-50 p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon rounded-circle bg-dark bg-opacity-50 p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @else
        <!-- Fallback Default Hero Showcase Banner (with Unsplash background image) -->
        <div id="hero-banner" class="hero-banner rounded-4 p-4 p-sm-5 mb-4 mb-md-5 text-white position-relative overflow-hidden shadow-sm" 
             style="background: linear-gradient(135deg, rgba(15,23,42,0.88) 0%, rgba(30,27,75,0.85) 50%, rgba(49,46,129,0.8) 100%), url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;">
            
            <!-- Background Overlay Shapes -->
            <div class="position-absolute top-0 end-0 p-5 opacity-10 pointer-events-none d-none d-md-block">
                <i class="fa-solid fa-bag-shopping" style="font-size: 16rem; margin-top: -30px; margin-right: -40px;"></i>
            </div>

            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-9 col-xl-8">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill text-uppercase mb-3 shadow-sm" style="letter-spacing: 0.05em; font-size: 0.75rem;">
                        <i class="fa-solid fa-sparkles me-1"></i> New Season Arrivals 2026
                    </span>
                    <h1 class="fw-extrabold display-6 display-md-5 mb-3 text-white">
                        Elevate Your Lifestyle with Premium Goods
                    </h1>
                    <p class="lead text-white-50 mb-4 fs-6 fs-md-5" style="max-width: 600px;">
                        Explore curated collections of top-rated electronics, fashion apparel, home essentials, and lifestyle accessories at unbeatable prices.
                    </p>
                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-2 gap-sm-3">
                        <a href="#products-grid" class="btn btn-primary btn-md btn-md-lg px-4 py-2.5 py-sm-3 fw-bold w-100 w-sm-auto text-center">
                            <i class="fa-solid fa-grid-2 me-2"></i> Shop All Products
                        </a>
                        <a href="{{ route('products.index', ['category' => 1]) }}" class="btn btn-outline-light btn-md btn-md-lg px-4 py-2.5 py-sm-3 fw-semibold w-100 w-sm-auto text-center">
                            <i class="fa-solid fa-bolt me-2"></i> Electronics Sale
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Trust Features Bar -->
    <div class="row g-2 g-sm-3 mb-4 mb-md-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 p-sm-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <div class="feature-icon-box flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.1rem;">
                        <i class="fa-solid fa-truck-fast text-primary"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.85rem;">Free Delivery</h6>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.75rem;">Orders &ge; ₹1,000</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 p-sm-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <div class="feature-icon-box flex-shrink-0" style="background: #ecfdf5; color: #10b981; width: 42px; height: 42px; font-size: 1.1rem;">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.85rem;">Secure Payment</h6>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.75rem;">Razorpay 256-bit SSL</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 p-sm-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <div class="feature-icon-box flex-shrink-0" style="background: #fef3c7; color: #d97706; width: 42px; height: 42px; font-size: 1.1rem;">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.85rem;">Easy Returns</h6>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.75rem;">7 days hassle-free</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 p-sm-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <div class="feature-icon-box flex-shrink-0" style="background: #f3e8ff; color: #9333ea; width: 42px; height: 42px; font-size: 1.1rem;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.85rem;">24/7 Support</h6>
                        <span class="text-muted text-truncate d-block" style="font-size: 0.75rem;">Dedicated assistance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls Header -->
    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between mb-3 mb-md-4 gap-3" id="products-grid">
        <div>
            <h3 class="fw-bold text-dark mb-1 fs-4 fs-md-3">Our Products Catalog</h3>
            <p class="text-muted small mb-0">Showing {{ $products->total() }} items available for immediate dispatch</p>
        </div>

        <!-- Search Input -->
        <div class="w-100 w-md-auto" style="max-width: 420px;">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="input-group shadow-sm w-100">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0 ps-0 bg-white" 
                           placeholder="Search products in catalog..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-3 px-sm-4 fw-bold">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Filter Pills (Scrollable on Mobile) -->
    <div class="category-pills-container mb-4">
        <div class="d-flex gap-2 overflow-x-auto pb-2 scrollbar-none flex-nowrap flex-md-wrap align-items-center">
            <a href="{{ route('products.index', array_filter(['search' => request('search')])) }}" 
               class="btn btn-sm {{ empty(request('category')) ? 'btn-primary shadow-sm' : 'btn-white border text-dark' }} rounded-pill px-3 px-sm-4 py-2 fw-semibold text-nowrap">
                <i class="fa-solid fa-layer-group me-1"></i> All Categories
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('products.index', array_filter(['category' => $cat->id, 'search' => request('search')])) }}" 
                   class="btn btn-sm {{ (string)request('category') === (string)$cat->id ? 'btn-primary shadow-sm' : 'btn-white border text-dark' }} rounded-pill px-3 px-sm-4 py-2 fw-semibold text-nowrap">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Filter Active Badge Indicator -->
    @if(!empty(request('search')) || !empty(request('category')))
        <div class="alert alert-light border shadow-sm rounded-3 py-2 px-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="small d-flex flex-wrap align-items-center gap-1">
                <span class="fw-semibold text-dark me-1"><i class="fa-solid fa-filter me-1 text-primary"></i> Active Filters:</span>
                @if(!empty(request('search')))
                    <span class="badge bg-secondary me-1">Search: "{{ request('search') }}"</span>
                @endif
                @if(!empty(request('category')))
                    <span class="badge bg-primary me-1">Category: {{ optional($categories->firstWhere('id', request('category')))->name }}</span>
                @endif
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-link text-danger text-decoration-none fw-semibold p-0">
                <i class="fa-solid fa-xmark me-1"></i> Clear All
            </a>
        </div>
    @endif

    <!-- Product Grid (2 Columns on Mobile, 3 on Tablet, 4 on Desktop) -->
    @if($products->count() > 0)
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 g-sm-3 g-md-4 mb-4 mb-md-5">
            @foreach($products as $product)
                <div class="col">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center overflow-x-auto py-2 my-4 w-100">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4 bg-white">
            <div class="card-body px-3">
                <div class="mb-3 text-muted">
                    <i class="fa-solid fa-boxes-packing fs-1 text-primary opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2 fs-5 fs-md-4">No Matching Products Found</h4>
                <p class="text-muted small mb-4 mx-auto" style="max-width: 420px;">
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
    /* Hide scrollbars for chrome, safari, firefox and opera */
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .category-pills-container {
        position: relative;
    }
    .btn-white {
        background-color: #ffffff;
    }
    .btn-white:hover {
        background-color: #f8fafc;
    }
</style>
@endpush
@endsection

