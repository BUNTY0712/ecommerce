@extends('layouts.app')

@section('title', 'Select Payment Method - StoreCraft')

@section('content')
<div class="container">
    
    <!-- Checkout Steps -->
    <div class="checkout-steps">
        <div class="step-item">
            <div class="step-number"><i class="fa-solid fa-check fs-7"></i></div>
            <span>Shipping</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item active">
            <div class="step-number"><i class="fa-solid fa-credit-card fs-7"></i></div>
            <span>Payment</span>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <div class="step-number">3</div>
            <span>Success</span>
        </div>
    </div>

    <div class="row g-4 justify-content-center mb-5">
        <!-- Payment Options Card -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-shield-heart me-2 text-primary"></i> Payment Gateway & Options
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Select your preferred payment method below to complete order.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.process') }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <!-- Payment Options Radio List -->
                        <div class="d-flex flex-column gap-3 mb-4">

                            <!-- Option 1: Razorpay -->
                            <div class="border rounded-3 p-3 shadow-sm bg-white cursor-pointer" id="optionRazorpayBox" style="transition: all 0.2s ease;">
                                <div class="form-check d-flex align-items-center gap-3">
                                    <input class="form-check-input fs-5 mt-0" type="radio" name="payment_method" id="methodRazorpay" value="razorpay" checked>
                                    <label class="form-check-label w-100 cursor-pointer" for="methodRazorpay">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark d-block fs-6">Razorpay Online Payment</span>
                                                <span class="text-muted small">Cards, UPI, NetBanking, Paytm, PhonePe, GPay (TEST Mode)</span>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7">
                                                <i class="fa-solid fa-bolt me-1"></i> Recommended
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Option 2: Cash on Delivery -->
                            <div class="border rounded-3 p-3 shadow-sm bg-white cursor-pointer" id="optionCodBox" style="transition: all 0.2s ease;">
                                <div class="form-check d-flex align-items-center gap-3">
                                    <input class="form-check-input fs-5 mt-0" type="radio" name="payment_method" id="methodCod" value="cod">
                                    <label class="form-check-label w-100 cursor-pointer" for="methodCod">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark d-block fs-6">Cash on Delivery (COD)</span>
                                                <span class="text-muted small">Pay cash directly to courier upon delivery</span>
                                            </div>
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 fs-7">
                                                <i class="fa-solid fa-money-bill-wave me-1"></i> Cash
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                        </div>

                        <!-- Shipping Address Summary Box -->
                        <div class="bg-light rounded-3 p-3 mb-4 small border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark"><i class="fa-solid fa-location-dot text-primary me-1"></i> Shipping Address:</strong>
                                <a href="{{ route('checkout.shipping') }}" class="text-primary fw-semibold text-decoration-none">Edit</a>
                            </div>
                            <p class="mb-0 text-secondary">
                                <strong>{{ $shippingData['first_name'] }} {{ $shippingData['last_name'] }}</strong> ({{ $shippingData['phone'] }})<br>
                                {{ $shippingData['address'] }} {{ $shippingData['address_line_2'] ? ', ' . $shippingData['address_line_2'] : '' }}<br>
                                {{ $shippingData['city'] }}, {{ $shippingData['state'] }} - {{ $shippingData['pincode'] }}, {{ $shippingData['country'] }}
                            </p>
                        </div>

                        <!-- Pay Button -->
                        <button type="button" id="payButton" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                            <i class="fa-solid fa-lock me-2"></i> Pay & Complete Order (₹{{ number_format($cartData['total'], 2) }})
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-dark">Order Items Invoice</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cartData['items'] as $item)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-2">
                                <div>
                                    <span class="fw-semibold text-dark">{{ $item['name'] }}</span>
                                    <span class="text-muted small d-block">Qty: {{ $item['quantity'] }} &times; ₹{{ number_format($item['price'], 2) }}</span>
                                </div>
                                <span class="fw-bold text-dark">₹{{ number_format($item['total'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-2 text-secondary">
                        <span>Items Subtotal</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($cartData['subtotal'], 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Shipping Charge</span>
                        @if($cartData['shipping'] == 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">FREE</span>
                        @else
                            <span class="fw-semibold text-dark">₹{{ number_format($cartData['shipping'], 2) }}</span>
                        @endif
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-dark">Total Amount</span>
                        <span class="fs-4 fw-bold text-primary">₹{{ number_format($cartData['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Razorpay Checkout JS SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payButton = document.getElementById('payButton');
        const paymentForm = document.getElementById('paymentForm');
        const razorpayInputId = document.getElementById('razorpay_payment_id');
        const razorpayInputOrder = document.getElementById('razorpay_order_id');
        const razorpayInputSig = document.getElementById('razorpay_signature');

        const optionRazorpayBox = document.getElementById('optionRazorpayBox');
        const optionCodBox = document.getElementById('optionCodBox');
        const radioRazorpay = document.getElementById('methodRazorpay');
        const radioCod = document.getElementById('methodCod');

        function updateSelectionStyles() {
            if (radioRazorpay.checked) {
                optionRazorpayBox.style.borderColor = '#4f46e5';
                optionRazorpayBox.style.borderWidth = '2px';
                optionRazorpayBox.style.backgroundColor = '#f5f3ff';
                optionCodBox.style.borderColor = '#e2e8f0';
                optionCodBox.style.borderWidth = '1px';
                optionCodBox.style.backgroundColor = '#ffffff';
            } else {
                optionCodBox.style.borderColor = '#4f46e5';
                optionCodBox.style.borderWidth = '2px';
                optionCodBox.style.backgroundColor = '#f5f3ff';
                optionRazorpayBox.style.borderColor = '#e2e8f0';
                optionRazorpayBox.style.borderWidth = '1px';
                optionRazorpayBox.style.backgroundColor = '#ffffff';
            }
        }

        radioRazorpay.addEventListener('change', updateSelectionStyles);
        radioCod.addEventListener('change', updateSelectionStyles);
        updateSelectionStyles();

        payButton.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (selectedMethod === 'cod') {
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Placing COD Order...';
                paymentForm.submit();
                return;
            }

            if (selectedMethod === 'razorpay') {
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Opening Gateway...';

                fetch("{{ route('checkout.razorpayOrder') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert("Error initializing payment: " + (data.error || "Unknown error"));
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay & Complete Order';
                        return;
                    }

                    if (data.is_mock || !data.key_id || data.key_id === 'rzp_test_mock_key') {
                        setTimeout(() => {
                            razorpayInputOrder.value = data.razorpay_order_id;
                            razorpayInputId.value = "pay_mock_" + Math.random().toString(36).substring(2, 12);
                            razorpayInputSig.value = "mock_signature";
                            paymentForm.submit();
                        }, 700);
                    } else {
                        const options = {
                            "key": data.key_id,
                            "amount": data.amount,
                            "currency": "INR",
                            "name": "StoreCraft E-Commerce",
                            "description": "Order Payment",
                            "image": "https://cdn-icons-png.flaticon.com/512/3081/3081559.png",
                            "order_id": data.razorpay_order_id,
                            "handler": function (response) {
                                razorpayInputOrder.value = response.razorpay_order_id;
                                razorpayInputId.value = response.razorpay_payment_id;
                                razorpayInputSig.value = response.razorpay_signature;
                                paymentForm.submit();
                            },
                            "prefill": {
                                "name": "{{ $shippingData['first_name'] }} {{ $shippingData['last_name'] }}",
                                "email": "{{ $shippingData['email'] }}",
                                "contact": "{{ $shippingData['phone'] }}"
                            },
                            "theme": {
                                "color": "#4f46e5"
                            },
                            "modal": {
                                "ondismiss": function() {
                                    payButton.disabled = false;
                                    payButton.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay & Complete Order';
                                }
                            }
                        };
                        const rzp = new Razorpay(options);
                        rzp.open();
                    }
                })
                .catch(err => {
                    console.error("Razorpay Error:", err);
                    alert("Payment system encountered an error. Please try again.");
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay & Complete Order';
                });
            }
        });
    });
</script>
@endpush
