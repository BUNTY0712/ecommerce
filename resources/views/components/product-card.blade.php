@props(['product'])

@php
    $effectivePrice = ($product->discount_price && $product->discount_price < $product->price)
        ? $product->discount_price
        : $product->price;

    $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
    $discountPercent = $hasDiscount
        ? round((($product->price - $product->discount_price) / $product->price) * 100)
        : 0;

    $imagePath = $product->image
        ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
        : asset('storage/products/placeholder.svg');

    // Deterministic rating calculation for modern UI demo
    $rating = number_format(4.2 + (($product->id * 3) % 8) / 10, 1);
    $reviewsCount = 12 + ($product->id * 9);
@endphp

<div class="card h-100 product-card-hover overflow-hidden border-0 shadow-sm position-relative">
    <!-- Top Discount Badge -->
    @if($hasDiscount)
        <div class="position-absolute top-0 start-0 m-2 m-sm-2.5 z-2">
            <span class="badge badge-discount px-2 py-0.5" style="font-size: 0.7rem;">
                <i class="fa-solid fa-bolt me-1"></i> {{ $discountPercent }}% OFF
            </span>
        </div>
    @endif

    <!-- Product Image Box -->
    <div class="product-img-wrapper d-flex align-items-center justify-content-center p-2 p-sm-3">
        <a href="{{ route('products.show', $product->id) }}" class="d-block w-100 h-100 text-center d-flex align-items-center justify-content-center">
            <img src="{{ $imagePath }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid product-card-img" 
                 onerror="this.src='https://placehold.co/400x300/e2e8f0/475569?text=Product+Image'">
        </a>
    </div>

    <!-- Card Content -->
    <div class="card-body d-flex flex-column p-2.5 p-sm-3">
        <!-- Category & Stock Badges Row -->
        <div class="d-flex align-items-center justify-content-between gap-1 mb-1 mb-sm-1.5">
            @if(isset($product->category_name))
                <span class="badge badge-category text-truncate" style="font-size: 0.7rem; padding: 0.25em 0.6em;">
                    {{ $product->category_name }}
                </span>
            @endif
            @if($product->stock > 0)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5 ms-auto" style="font-size: 0.68rem;">In Stock</span>
            @else
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1.5 py-0.5 ms-auto" style="font-size: 0.68rem;">Out of Stock</span>
            @endif
        </div>

        <!-- Product Name -->
        <h6 class="fw-bold text-dark mb-1 text-truncate product-card-title" title="{{ $product->name }}">
            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none hover-primary">
                {{ $product->name }}
            </a>
        </h6>

        <!-- Star Ratings (Single Row) -->
        <div class="d-flex align-items-center gap-1 mb-2 text-nowrap overflow-hidden">
            <div class="rating-stars" style="font-size: 0.72rem;">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-stroke"></i>
            </div>
            <span class="fw-bold text-dark ms-0.5" style="font-size: 0.75rem;">{{ $rating }}</span>
            <span class="text-muted" style="font-size: 0.72rem;">({{ $reviewsCount }})</span>
        </div>

        <!-- Price Footer & Buttons -->
        <div class="mt-auto pt-2 border-top">
            <div class="d-flex align-items-baseline gap-1 mb-2 text-nowrap overflow-hidden">
                <span class="fw-bold text-dark fs-6 fs-sm-5">₹{{ number_format($effectivePrice, $effectivePrice == floor($effectivePrice) ? 0 : 2) }}</span>
                @if($hasDiscount)
                    <span class="text-muted text-decoration-line-through small" style="font-size: 0.75rem;">₹{{ number_format($product->price, $product->price == floor($product->price) ? 0 : 2) }}</span>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="row g-1.5 g-sm-2">
                <div class="col-6">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold text-nowrap d-flex align-items-center justify-content-center py-1.5 px-1 px-sm-2 product-card-btn">
                        Details
                    </a>
                </div>
                <div class="col-6">
                    <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold text-nowrap d-flex align-items-center justify-content-center py-1.5 px-1 px-sm-2 product-card-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus me-1"></i> Add
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .product-img-wrapper {
        height: 145px;
    }
    .product-card-img {
        max-height: 120px;
        object-fit: contain;
        width: 100%;
    }
    .product-card-title {
        font-size: 0.85rem;
    }
    .product-card-btn {
        font-size: 0.78rem;
    }
    @media (min-width: 576px) {
        .product-img-wrapper {
            height: 190px;
        }
        .product-card-img {
            max-height: 155px;
        }
        .product-card-title {
            font-size: 0.925rem;
        }
        .product-card-btn {
            font-size: 0.825rem;
        }
    }
</style>
