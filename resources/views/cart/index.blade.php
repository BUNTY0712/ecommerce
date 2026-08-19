@extends('layouts.app')

@section('title', 'Shopping Cart - StoreCraft')

@section('content')
<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-extrabold text-dark mb-0">
            <i class="fa-solid fa-cart-shopping me-2 text-primary"></i> Shopping Cart
            @if(!empty($cartData['items']))
                <span class="fs-6 fw-normal text-muted">({{ $cartData['item_count'] }} items)</span>
            @endif
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
            <i class="fa-solid fa-arrow-left me-1"></i> Continue Shopping
        </a>
    </div>

    @if(!empty($cartData['items']))
        <div class="row g-4 mb-5">
            <!-- Left: Cart Items Table -->
            <div class="col-lg-8">

                <!-- Free Shipping Progress Alert -->
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);">
                    <div class="card-body p-4">
                        @if($cartData['subtotal'] >= 1000)
                            <div class="d-flex align-items-center gap-3 text-success">
                                <i class="fa-solid fa-circle-check fs-3"></i>
                                <div>
                                    <strong class="fs-6">Congratulations! You unlocked FREE Express Shipping!</strong>
                                    <p class="mb-0 small text-success-emphasis">Your order qualifies for 0 shipping fees.</p>
                                </div>
                            </div>
                        @else
                            @php
                                $remainingForFreeShipping = 1000 - $cartData['subtotal'];
                                $progressPercent = min(100, round(($cartData['subtotal'] / 1000) * 100));
                            @endphp
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-primary small">
                                    <i class="fa-solid fa-truck-fast me-1"></i> Add <strong>₹{{ number_format($remainingForFreeShipping, 2) }}</strong> more for FREE Shipping!
                                </span>
                                <span class="small fw-bold text-dark">{{ $progressPercent }}%</span>
                            </div>
                            <div class="progress bg-white shadow-inner" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items Table Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="py-3 px-4">Item Details</th>
                                        <th scope="col" class="py-3 text-center">Unit Price</th>
                                        <th scope="col" class="py-3 text-center" style="min-width: 140px;">Quantity</th>
                                        <th scope="col" class="py-3 text-end pe-4">Subtotal</th>
                                        <th scope="col" class="py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartData['items'] as $item)
                                        @php
                                            $imagePath = $item['image']
                                                ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']))
                                                : asset('storage/products/placeholder.svg');
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <a href="{{ route('products.show', $item['product_id']) }}" class="flex-shrink-0">
                                                        <img src="{{ $imagePath }}" 
                                                             alt="{{ $item['name'] }}" 
                                                             class="rounded border bg-light p-1" 
                                                             style="width: 70px; height: 70px; object-fit: contain;"
                                                             onerror="this.src='https://placehold.co/100x100/e2e8f0/475569?text=Product'">
                                                    </a>
                                                    <div>
                                                        <a href="{{ route('products.show', $item['product_id']) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block mb-1">
                                                            {{ $item['name'] }}
                                                        </a>
                                                        <span class="badge bg-success-subtle text-success fs-8">In Stock ({{ $item['max_stock'] }})</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-center fw-semibold text-dark">
                                                ₹{{ number_format($item['price'], 2) }}
                                            </td>

                                            <!-- Quantity controls -->
                                            <td class="text-center">
                                                <div class="d-inline-flex align-items-center border rounded-3 bg-white p-1">
                                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                                        <button type="submit" class="btn btn-sm btn-light border-0 px-2" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                            <i class="fa-solid fa-minus fs-8"></i>
                                                        </button>
                                                    </form>

                                                    <span class="fw-bold px-3 text-dark">{{ $item['quantity'] }}</span>

                                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                                        <button type="submit" class="btn btn-sm btn-light border-0 px-2" {{ $item['quantity'] >= $item['max_stock'] ? 'disabled' : '' }}>
                                                            <i class="fa-solid fa-plus fs-8"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                            <td class="text-end fw-bold text-primary pe-4">
                                                ₹{{ number_format($item['total'], 2) }}
                                            </td>

                                            <!-- Action -->
                                            <td class="text-center">
                                                <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove item">
                                                        <i class="fa-solid fa-trash-can fs-6"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0 text-dark">Order Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 text-secondary">
                            <span>Bag Subtotal</span>
                            <span class="fw-bold text-dark">₹{{ number_format($cartData['subtotal'], 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-secondary">
                            <span>Estimated Shipping</span>
                            @if($cartData['shipping'] == 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">FREE</span>
                            @else
                                <span class="fw-bold text-dark">₹{{ number_format($cartData['shipping'], 2) }}</span>
                            @endif
                        </div>

                        <!-- Promo Code Input Visual -->
                        <div class="my-3">
                            <label class="form-label small fw-semibold text-muted">Promo Code / Coupon</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Enter coupon (e.g. STORE10)" value="STORE10">
                                <button class="btn btn-outline-secondary btn-sm" type="button">Apply</button>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold text-dark">Total Amount</span>
                            <span class="fs-4 fw-bold text-primary">₹{{ number_format($cartData['total'], 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.shipping') }}" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>

                        <div class="mt-4 pt-3 border-top text-center text-muted small">
                            <i class="fa-solid fa-lock text-success me-1"></i> Guaranteed 256-bit Encrypted Checkout
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4 bg-white">
            <div class="card-body">
                <div class="mb-3 text-muted">
                    <i class="fa-solid fa-cart-arrow-down fs-1 text-primary opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Your Shopping Cart is Empty</h4>
                <p class="text-muted small mb-4" style="max-width: 400px; margin: 0 auto;">
                    You don't have any products in your cart yet. Explore our latest arrivals and discover great deals today!
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-store me-2"></i> Browse Products Now
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
