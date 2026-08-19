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
        <div class="position-absolute top-0 start-0 m-3 z-2">
            <span class="badge badge-discount">
                <i class="fa-solid fa-bolt me-1"></i> SAVE {{ $discountPercent }}%
            </span>
        </div>
    @endif

    <!-- Product Image -->
    <div class="product-img-wrapper d-flex align-items-center justify-content-center p-4" style="height: 220px;">
        <a href="{{ route('products.show', $product->id) }}" class="d-block w-100 text-center">
            <img src="{{ $imagePath }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid" 
                 style="max-height: 180px; object-fit: contain; width: 100%;"
                 onerror="this.src='https://placehold.co/400x300/e2e8f0/475569?text=Product+Image'">
        </a>
    </div>

    <!-- Card Content -->
    <div class="card-body d-flex flex-column p-3">
        @if(isset($product->category_name))
            <div class="mb-1">
                <span class="badge badge-category">
                    {{ $product->category_name }}
                </span>
            </div>
        @endif

        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none hover-primary">
                {{ $product->name }}
            </a>
        </h6>

        <!-- Star Ratings -->
        <div class="d-flex align-items-center gap-1 mb-2">
            <div class="rating-stars">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-stroke"></i>
            </div>
            <span class="small fw-semibold text-dark me-1 ms-1">{{ $rating }}</span>
            <span class="small text-muted">({{ $reviewsCount }})</span>
        </div>

        <!-- Short description snippet -->
        @if(!empty($product->short_description))
            <p class="text-muted small mb-3 text-truncate-2" style="font-size: 0.825rem; min-height: 2.4em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $product->short_description }}
            </p>
        @endif

        <!-- Price & Stock Footer -->
        <div class="mt-auto pt-2 border-top">
            <div class="d-flex align-items-baseline justify-content-between mb-2">
                <div>
                    <span class="fs-5 fw-bold text-dark">₹{{ number_format($effectivePrice, 2) }}</span>
                    @if($hasDiscount)
                        <span class="text-muted text-decoration-line-through small ms-1">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                @if($product->stock > 0)
                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-8 px-2 py-1">In Stock</span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fs-8 px-2 py-1">Out of Stock</span>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold py-2">
                        Details
                    </a>
                </div>
                <div class="col-6">
                    <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold py-2" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus me-1"></i> Add
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
