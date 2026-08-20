@extends('layouts.app')

@section('title', 'Shipping Details - Checkout - StoreCraft')

@section('content')
<div class="container">
    
    <!-- Checkout Steps -->
    <div class="checkout-steps">
        <div class="step-item active">
            <div class="step-number"><i class="fa-solid fa-truck-fast fs-7"></i></div>
            <span>Shipping</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <div class="step-number">2</div>
            <span>Payment</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <div class="step-number">3</div>
            <span>Success</span>
        </div>
    </div>

    <div class="row g-4 justify-content-center mb-5">
        <!-- Shipping Form Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-address-card me-2 text-primary"></i> Customer & Shipping Information
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Please enter the delivery address for your order.</p>
                </div>
                <div class="card-body p-4">
                    @if(isset($deliveryMode) && $deliveryMode === 'restricted')
                        <div class="alert alert-warning border-0 rounded-3 mb-4 d-flex align-items-center gap-2 py-2 px-3 small" style="background-color: #fffbeb; color: #92400e;">
                            <i class="fa-solid fa-truck-clock fs-4 text-warning"></i>
                            <div>
                                <strong>Limited Area Delivery Active:</strong> Our store currently delivers to select local pincodes only. Enter your pincode to verify delivery availability.
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('checkout.storeShipping') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- First Name -->
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-semibold text-dark">First Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="first_name" 
                                       id="first_name" 
                                       class="form-control py-2 @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name', $shippingData['first_name'] ?? '') }}" 
                                       placeholder="e.g. Rahul" 
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-semibold text-dark">Last Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="last_name" 
                                       id="last_name" 
                                       class="form-control py-2 @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name', $shippingData['last_name'] ?? '') }}" 
                                       placeholder="e.g. Sharma" 
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control py-2 @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $shippingData['email'] ?? '') }}" 
                                       placeholder="e.g. rahul.sharma@example.com" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-dark">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       class="form-control py-2 @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $shippingData['phone'] ?? '') }}" 
                                       placeholder="e.g. 9876543210" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address Line 1 -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold text-dark">Street Address <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="address" 
                                       id="address" 
                                       class="form-control py-2 @error('address') is-invalid @enderror" 
                                       value="{{ old('address', $shippingData['address'] ?? '') }}" 
                                       placeholder="House No., Flat, Building, Street Name" 
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address Line 2 -->
                            <div class="col-12">
                                <label for="address_line_2" class="form-label fw-semibold text-dark">Address Line 2 <span class="text-muted">(Optional)</span></label>
                                <input type="text" 
                                       name="address_line_2" 
                                       id="address_line_2" 
                                       class="form-control py-2 @error('address_line_2') is-invalid @enderror" 
                                       value="{{ old('address_line_2', $shippingData['address_line_2'] ?? '') }}" 
                                       placeholder="Landmark, Suite, Apartment (Optional)">
                                @error('address_line_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-semibold text-dark">City <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       class="form-control py-2 @error('city') is-invalid @enderror" 
                                       value="{{ old('city', $shippingData['city'] ?? '') }}" 
                                       placeholder="City" 
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- State -->
                            <div class="col-md-4">
                                <label for="state" class="form-label fw-semibold text-dark">State <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="state" 
                                       id="state" 
                                       class="form-control py-2 @error('state') is-invalid @enderror" 
                                       value="{{ old('state', $shippingData['state'] ?? '') }}" 
                                       placeholder="State" 
                                       required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pincode -->
                            <div class="col-md-4">
                                <label for="pincode" class="form-label fw-semibold text-dark">Pincode / Zip <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           name="pincode" 
                                           id="pincode" 
                                           class="form-control py-2 @error('pincode') is-invalid @enderror" 
                                           value="{{ old('pincode', $shippingData['pincode'] ?? '') }}" 
                                           placeholder="e.g. 110001" 
                                           maxlength="10"
                                           required
                                           oninput="checkPincodeRealtime(this.value)">
                                    <button type="button" class="btn btn-outline-secondary" onclick="checkPincodeRealtime(document.getElementById('pincode').value)">
                                        <i class="fa-solid fa-location-crosshairs"></i> Check
                                    </button>
                                </div>
                                <div id="pincodeStatus" class="mt-1"></div>
                                @error('pincode')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div class="col-12">
                                <label for="country" class="form-label fw-semibold text-dark">Country <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="country" 
                                       id="country" 
                                       class="form-control py-2 @error('country') is-invalid @enderror" 
                                       value="{{ old('country', $shippingData['country'] ?? 'India') }}" 
                                       placeholder="Country" 
                                       required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary px-4 fw-semibold">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                Continue to Payment <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-dark">Itemized Order Summary</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cartData['items'] as $item)
                            @php
                                $imagePath = $item['image']
                                    ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']))
                                    : asset('storage/products/placeholder.svg');
                            @endphp
                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between border-0 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $imagePath }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="rounded border bg-light" 
                                         style="width: 42px; height: 42px; object-fit: contain;">
                                    <div>
                                        <span class="small fw-semibold text-dark d-block text-truncate" style="max-width: 140px;">{{ $item['name'] }}</span>
                                        <span class="small text-muted">Qty: {{ $item['quantity'] }}</span>
                                    </div>
                                </div>
                                <span class="small text-dark fw-bold">₹{{ number_format($item['total'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small text-secondary mb-1">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($cartData['subtotal'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mb-2">
                        <span>Shipping</span>
                        @if($cartData['shipping'] == 0)
                            <span class="text-success fw-bold">FREE</span>
                        @else
                            <span>₹{{ number_format($cartData['shipping'], 2) }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between fw-bold text-dark fs-6 pt-2 border-top">
                        <span>Total Payable</span>
                        <span class="text-primary">₹{{ number_format($cartData['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let checkTimeout = null;
function checkPincodeRealtime(code) {
    const statusDiv = document.getElementById('pincodeStatus');
    if (!statusDiv) return;

    const trimmed = code.trim();
    if (trimmed.length < 3) {
        statusDiv.innerHTML = '';
        return;
    }

    clearTimeout(checkTimeout);
    checkTimeout = setTimeout(() => {
        statusDiv.innerHTML = '<span class="spinner-border spinner-border-sm text-primary me-1"></span><span class="text-muted small">Checking delivery...</span>';

        fetch(`/pincode/check/${encodeURIComponent(trimmed)}`)
            .then(res => res.json())
            .then(data => {
                if (data.serviceable) {
                    statusDiv.innerHTML = '<div class="alert alert-success py-1 px-2 rounded small mb-0 mt-1 d-inline-flex align-items-center gap-1"><i class="fa-solid fa-circle-check text-success"></i> <span class="fw-semibold">Delivery Available</span></div>';
                } else {
                    statusDiv.innerHTML = '<div class="alert alert-danger py-1 px-2 rounded small mb-0 mt-1 d-inline-flex align-items-center gap-1"><i class="fa-solid fa-circle-xmark text-danger"></i> <span class="fw-semibold">' + data.message + '</span></div>';
                }
            })
            .catch(() => {
                statusDiv.innerHTML = '';
            });
    }, 350);
}

document.addEventListener('DOMContentLoaded', () => {
    const pincodeInput = document.getElementById('pincode');
    if (pincodeInput && pincodeInput.value) {
        checkPincodeRealtime(pincodeInput.value);
    }
});
</script>
@endpush
