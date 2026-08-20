@php
    $siteName = \App\Models\Setting::get('site_name', 'StoreCraft');
    $siteLogo = \App\Models\Setting::get('site_logo');
@endphp
<footer class="bg-dark text-white pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: Brand Info -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
                    @if($siteLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($siteLogo))
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="max-height: 38px; object-fit: contain;">
                    @else
                        <i class="fa-solid fa-bag-shopping text-primary"></i> {{ $siteName }}
                    @endif
                </h5>
                <p class="text-secondary small mb-3">
                    Your one-stop modern destination for premium electronics, fashion, home essentials, and lifestyle products.
                </p>
                <div class="d-flex gap-3 text-secondary">
                    <a href="#" class="text-secondary text-decoration-none"><i class="fa-brands fa-facebook fs-5"></i></a>
                    <a href="#" class="text-secondary text-decoration-none"><i class="fa-brands fa-twitter fs-5"></i></a>
                    <a href="#" class="text-secondary text-decoration-none"><i class="fa-brands fa-instagram fs-5"></i></a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-light mb-3">Quick Navigation</h6>
                <ul class="list-unstyled text-secondary small mb-0">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Home Page</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}" class="text-secondary text-decoration-none">All Products</a></li>
                    <li class="mb-2"><a href="{{ route('cart.index') }}" class="text-secondary text-decoration-none">Shopping Cart</a></li>
                    <li class="mb-2"><a href="{{ route('checkout.shipping') }}" class="text-secondary text-decoration-none">Checkout</a></li>
                </ul>
            </div>

            <!-- Col 3: Customer Care -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-light mb-3">Information</h6>
                <ul class="list-unstyled text-secondary small mb-0">
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Contact Support</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Col 4: Customer Support -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-light mb-3">Help</h6>
                <p class="text-secondary small mb-1"><i class="fa-solid fa-headset me-2 text-primary"></i> 24/7 Support</p>
                <p class="text-secondary small mb-1"><i class="fa-solid fa-truck-fast me-2 text-primary"></i> Fast Delivery</p>
                <p class="text-secondary small mb-0"><i class="fa-solid fa-shield-halved me-2 text-primary"></i> Secure Payments</p>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-secondary small">
            <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} StoreCraft E-Commerce. All rights reserved.</p>
            <div class="d-flex gap-3">
                <span><i class="fa-brands fa-cc-visa fs-4 me-2"></i><i class="fa-brands fa-cc-mastercard fs-4 me-2"></i><i class="fa-solid fa-wallet fs-4"></i></span>
            </div>
        </div>
    </div>
</footer>
