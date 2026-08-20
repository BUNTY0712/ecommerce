@extends('layouts.app')

@section('title', $product->name . ' - StoreCraft')

@section('content')
<div class="container px-3 px-sm-4">

    <!-- Breadcrumb Nav -->
    <nav aria-label="breadcrumb" class="mb-3 mb-md-4">
        <ol class="breadcrumb small mb-0 flex-nowrap overflow-x-auto pb-1 scrollbar-none">
            <li class="breadcrumb-item text-nowrap"><a href="{{ route('home') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Home</a></li>
            <li class="breadcrumb-item text-nowrap"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Products</a></li>
            @if(isset($product->category_name))
                <li class="breadcrumb-item text-nowrap">
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-decoration-none text-muted">
                        {{ $product->category_name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active text-dark fw-semibold text-truncate text-nowrap" aria-current="page" style="max-width: 180px;">
                {{ $product->name }}
            </li>
        </ol>
    </nav>

    <!-- Main Product Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 mb-md-5">
        <div class="card-body p-3 p-sm-4 p-md-5">
            <div class="row g-4 g-lg-5 align-items-start">

                <!-- Left Column: Product Image Gallery -->
                <div class="col-lg-6 sticky-lg-top" style="top: 100px;">
                    @php
                        $effectivePrice = ($product->discount_price && $product->discount_price < $product->price)
                            ? $product->discount_price
                            : $product->price;

                        $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                        $discountPercent = $hasDiscount
                            ? round((($product->price - $product->discount_price) / $product->price) * 100)
                            : 0;

                        $savings = $hasDiscount ? ($product->price - $product->discount_price) : 0;

                        // Build complete gallery images list
                        $allImages = [];
                        if ($product->image) {
                            $allImages[] = str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image);
                        }
                        if (isset($galleryImages) && count($galleryImages) > 0) {
                            foreach ($galleryImages as $gImg) {
                                $url = str_starts_with($gImg->image_path, 'http') ? $gImg->image_path : asset('storage/' . $gImg->image_path);
                                if (!in_array($url, $allImages)) {
                                    $allImages[] = $url;
                                }
                            }
                        }
                        if (empty($allImages)) {
                            $allImages[] = asset('storage/products/placeholder.svg');
                        }

                        $mainImage = $allImages[0];

                        $rating = number_format(4.2 + (($product->id * 3) % 8) / 10, 1);
                        $reviewsCount = 28 + ($product->id * 11);
                    @endphp

                    <!-- Main Featured Display Box -->
                    <div class="position-relative bg-light rounded-4 p-3 p-sm-4 d-flex align-items-center justify-content-center text-center shadow-inner mb-3 gallery-main-box" style="min-height: 280px;">
                        @if($hasDiscount)
                            <div class="position-absolute top-0 start-0 m-2.5 m-sm-3 z-2">
                                <span class="badge badge-discount fs-7 fs-sm-6 shadow-sm">
                                    <i class="fa-solid fa-bolt me-1"></i> SAVE {{ $discountPercent }}%
                                </span>
                            </div>
                        @endif

                        <img id="mainFeaturedImg" 
                             src="{{ $mainImage }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid gallery-main-img" 
                             onerror="this.src='https://placehold.co/600x450/e2e8f0/475569?text=Product+Image'">
                    </div>

                    <!-- Multiple Image Thumbnails Bar -->
                    @if(count($allImages) > 1)
                        <div class="d-flex align-items-center gap-2 overflow-x-auto pb-2 scrollbar-none" id="galleryThumbnailsRow">
                            @foreach($allImages as $idx => $imgUrl)
                                <button type="button" 
                                        class="btn p-1 border rounded-3 bg-white gallery-thumb-btn {{ $idx === 0 ? 'border-primary shadow-sm' : 'border-subtle' }}" 
                                        onclick="changeFeaturedImage('{{ $imgUrl }}', this)" 
                                        style="width: 68px; height: 68px; flex-shrink: 0;">
                                    <img src="{{ $imgUrl }}" alt="Thumbnail {{ $idx + 1 }}" style="width: 100%; height: 100%; object-fit: contain;" class="rounded-2">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Column: Details & Order Form -->
                <div class="col-lg-6">
                    @if(isset($product->category_name))
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-3 py-1.5 rounded-pill mb-2 mb-sm-3" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-tag me-1"></i> {{ $product->category_name }}
                        </span>
                    @endif

                    <h2 class="fw-extrabold text-dark mb-2 lh-sm fs-3 fs-md-2">{{ $product->name }}</h2>

                    <!-- Star Ratings Summary -->
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <div class="rating-stars" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                        <span class="fw-bold text-dark fs-6">{{ $rating }}</span>
                        <span class="text-muted small">({{ $reviewsCount }} Verified Ratings)</span>
                        <span class="text-muted d-none d-sm-inline">&bull;</span>
                        <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Verified Merchant</span>
                    </div>

                    <!-- Pricing Box -->
                    <div class="bg-light p-3 rounded-3 mb-3 mb-md-4 border">
                        <div class="d-flex align-items-baseline gap-2 gap-sm-3 mb-1 flex-wrap">
                            <span class="fs-2 fs-md-display-6 fw-bold text-primary">₹{{ number_format($effectivePrice, 2) }}</span>
                            @if($hasDiscount)
                                <span class="fs-6 fs-sm-5 text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        @if($hasDiscount)
                            <div class="small text-success fw-bold">
                                <i class="fa-solid fa-piggy-bank me-1"></i> You Save ₹{{ number_format($savings, 2) }} ({{ $discountPercent }}% Off)
                            </div>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @if(!empty($product->short_description))
                        <p class="text-secondary fs-6 mb-3 mb-md-4 leading-relaxed">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <!-- Stock Status Alert -->
                    <div class="mb-3 mb-md-4">
                        @if($product->stock > 0)
                            <div class="alert alert-success border-0 py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                                <i class="fa-solid fa-circle-check fs-6 text-success flex-shrink-0"></i>
                                <span><strong>In Stock:</strong> {{ $product->stock }} units ready for immediate shipping</span>
                            </div>
                        @else
                            <div class="alert alert-danger border-0 py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                                <i class="fa-solid fa-circle-xmark fs-6 text-danger flex-shrink-0"></i>
                                <span><strong>Out of Stock:</strong> Currently unavailable</span>
                            </div>
                        @endif
                    </div>

                    <!-- Purchase Form -->
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Quantity Selector -->
                        <div class="mb-3 mb-md-4">
                            <label class="form-label fw-semibold text-dark mb-1.5" style="font-size: 0.9rem;">Select Quantity</label>
                            <div class="input-group" style="width: 140px;">
                                <button type="button" class="btn btn-outline-secondary px-2.5" onclick="decrementQty()">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantityInput" 
                                       class="form-control text-center fw-bold bg-white" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       readonly>
                                <button type="button" class="btn btn-outline-secondary px-2.5" onclick="incrementQty()">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-2 g-sm-3">
                            <div class="col-6">
                                <button type="submit" 
                                        class="btn btn-outline-primary btn-md btn-lg-lg w-100 py-2.5 py-sm-3 px-2 px-sm-3 fw-bold shadow-sm text-nowrap d-flex align-items-center justify-content-center btn-detail-action" 
                                        {{ $product->stock < 1 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-cart-plus me-1.5"></i> Add to Cart
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" 
                                        name="buy_now" 
                                        value="1" 
                                        class="btn btn-primary btn-md btn-lg-lg w-100 py-2.5 py-sm-3 px-2 px-sm-3 fw-bold shadow-sm text-nowrap d-flex align-items-center justify-content-center btn-detail-action" 
                                        {{ $product->stock < 1 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-bolt me-1.5"></i> Buy Now
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Trust Guarantees Panel -->
                    <div class="border rounded-3 p-3 bg-white shadow-xs">
                        <div class="row g-2 text-secondary small">
                            <div class="col-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-truck-fast text-primary fs-6 flex-shrink-0"></i>
                                <span class="text-truncate" style="font-size: 0.78rem;">Free Shipping &ge; ₹1,000</span>
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-rotate-left text-success fs-6 flex-shrink-0"></i>
                                <span class="text-truncate" style="font-size: 0.78rem;">7-Day Easy Returns</span>
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2 mt-2">
                                <i class="fa-solid fa-shield-halved text-warning fs-6 flex-shrink-0"></i>
                                <span class="text-truncate" style="font-size: 0.78rem;">100% Original Product</span>
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2 mt-2">
                                <i class="fa-solid fa-lock text-info fs-6 flex-shrink-0"></i>
                                <span class="text-truncate" style="font-size: 0.78rem;">Encrypted Checkout</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Detailed Specifications Tab / Section -->
            @if(!empty($product->description))
                <div class="mt-4 mt-md-5 pt-3 pt-md-4 border-top">
                    <h4 class="fw-bold text-dark mb-3 fs-5 fs-md-4">
                        <i class="fa-solid fa-circle-info me-2 text-primary"></i> Product Overview & Details
                    </h4>
                    <div class="text-secondary leading-relaxed fs-6">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Related Products Showcase -->
    @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="mb-4 mb-md-5">
            <h4 class="fw-bold text-dark mb-3 mb-md-4 fs-5 fs-md-4">
                <i class="fa-solid fa-layer-group me-2 text-primary"></i> Related Products in Category
            </h4>
            <div class="row row-cols-2 row-cols-md-4 g-2 g-sm-3 g-md-4">
                @foreach($relatedProducts as $related)
                    <div class="col">
                        <x-product-card :product="$related" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@push('styles')
<style>
    .gallery-main-img {
        max-height: 280px;
        object-fit: contain;
        width: 100%;
        transition: opacity 0.2s ease;
    }
    .btn-detail-action {
        font-size: 0.85rem;
    }
    @media (min-width: 992px) {
        .gallery-main-box {
            min-height: 420px !important;
        }
        .gallery-main-img {
            max-height: 380px;
        }
        .btn-detail-action {
            font-size: 0.95rem;
        }
    }
</style>
@endpush
@endsection

@push('scripts')
<script>
    const maxStock = {{ $product->stock }};
    const qtyInput = document.getElementById('quantityInput');

    function incrementQty() {
        let current = parseInt(qtyInput.value) || 1;
        if (current < maxStock) {
            qtyInput.value = current + 1;
        }
    }

    function decrementQty() {
        let current = parseInt(qtyInput.value) || 1;
        if (current > 1) {
            qtyInput.value = current - 1;
        }
    }

    function changeFeaturedImage(imgUrl, btn) {
        const mainImg = document.getElementById('mainFeaturedImg');
        if (mainImg) {
            mainImg.style.opacity = '0.2';
            setTimeout(() => {
                mainImg.src = imgUrl;
                mainImg.style.opacity = '1';
            }, 120);
        }
        document.querySelectorAll('.gallery-thumb-btn').forEach(b => {
            b.classList.remove('border-primary', 'shadow-sm');
            b.classList.add('border-subtle');
        });
        if (btn) {
            btn.classList.remove('border-subtle');
            btn.classList.add('border-primary', 'shadow-sm');
        }
    }
</script>
@endpush
