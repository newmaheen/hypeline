<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #f8f8f8;
            color: #111;
        }

        .checkout-page {
            padding: 45px 0 70px;
        }

        .checkout-title {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .checkout-card {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 12px;
            padding: 28px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            min-height: 48px;
            border-radius: 7px;
            border-color: #d8d8d8;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .form-control:focus {
            border-color: #111;
            box-shadow: 0 0 0 .15rem rgba(0, 0, 0, .08);
        }

        .payment-option {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: .2s;
        }

        .payment-option:hover {
            border-color: #111;
        }

        .payment-option input {
            margin-right: 8px;
        }

        .mobile-payment-box {
            background: #f7f7f7;
            border: 1px solid #e2e2e2;
            border-radius: 8px;
            padding: 20px;
            margin-top: 18px;
        }

        .order-summary {
            position: sticky;
            top: 20px;
        }

        .cart-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:first-child {
            padding-top: 0;
        }

        .cart-item-img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
            background: #f5f5f5;
            flex-shrink: 0;
        }

        .cart-item-name {
            font-weight: 700;
            font-size: 15px;
        }

        .cart-item-meta {
            color: #777;
            font-size: 13px;
            margin-top: 4px;
        }

        .cart-item-price {
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #555;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ddd;
            padding-top: 18px;
            margin-top: 18px;
            font-size: 20px;
            font-weight: 700;
        }

        .place-order-button {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 7px;
            background: #111;
            color: #fff;
            font-weight: 700;
            letter-spacing: .4px;
            margin-top: 22px;
            transition: .2s;
        }

        .place-order-button:hover:not(:disabled) {
            background: #333;
        }

        /* Disabled button */
        .place-order-button:disabled {
            background: #cfcfcf;
            color: #777;
            cursor: not-allowed;
            opacity: 1;
        }

        .back-cart {
            display: inline-block;
            margin-top: 20px;
            color: #111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-cart:hover {
            text-decoration: underline;
        }

        @media (max-width: 767px) {
            .checkout-page {
                padding: 25px 0 50px;
            }

            .checkout-title {
                font-size: 28px;
                margin-bottom: 25px;
            }

            .checkout-card {
                padding: 20px;
            }

            .order-summary {
                position: static;
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>

    @include('layouts.navigation')

    <div class="checkout-page">
        <div class="container">
            <h1 class="checkout-title">Checkout</h1>

            {{-- Errors --}}
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf

                <div class="row g-4">

                    {{-- LEFT SIDE --}}
                    <div class="col-12 col-lg-7">
                        <div class="checkout-card">

                            {{-- Customer Information --}}
                            <h2 class="section-title">Customer Information</h2>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    name="customer_name"
                                    class="form-control"
                                    placeholder="Enter your name"
                                    value="{{ old('customer_name') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Phone Number / মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input 
                                    type="tel" 
                                    name="customer_phone" 
                                    id="customer_phone" 
                                    class="form-control @error('customer_phone') is-invalid @enderror"
                                    value="{{ old('customer_phone') }}"
                                    placeholder="017XXXXXXXX"
                                    pattern="^(?:\+88|88)?01[3-9]\d{8}$"
                                    maxlength="14"
                                    required
                                >
                                @error('customer_phone')
                                    <div class="invalid-feedback text-danger d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Email
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <input
                                    type="email"
                                    name="customer_email"
                                    class="form-control"
                                    placeholder="Enter your email"
                                    value="{{ old('customer_email') }}"
                                >
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Shipping Address</label>
                                <textarea
                                    name="shipping_address"
                                    class="form-control"
                                    placeholder="Enter your full delivery address"
                                    required
                                >{{ old('shipping_address') }}</textarea>
                            </div>

                            <hr class="my-4">

                            {{-- Payment --}}
                            <h2 class="section-title">Payment Method</h2>

                            {{-- COD --}}
                            <label class="payment-option d-block">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="cod"
                                    checked
                                >
                                <strong>Cash on Delivery</strong>
                            </label>

                            {{-- bKash --}}
                            <label class="payment-option d-block">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="bkash"
                                >
                                <strong>bKash</strong>
                            </label>

                            {{-- Nagad --}}
                            <label class="payment-option d-block">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="nagad"
                                >
                                <strong>Nagad</strong>
                            </label>

                            {{-- Mobile Payment --}}
                            <div
                                id="mobilePaymentSection"
                                class="mobile-payment-box"
                                style="display: none;"
                            >
                                <p class="mb-3">
                                    Send payment to:
                                    <strong>01XXXXXXXXX</strong>
                                </p>

                                {{-- Transaction ID --}}
                                <div class="mb-3">
                                    <label class="form-label">Transaction ID</label>
                                    <input
                                        type="text"
                                        name="transaction_id"
                                        id="transaction_id"
                                        class="form-control"
                                        placeholder="Enter your Transaction ID"
                                        value="{{ old('transaction_id') }}"
                                    >
                                </div>

                                {{-- Payment Phone Number --}}
                                <div>
                                    <label class="form-label">Payment Phone Number</label>
                                    <input
                                        type="text"
                                        name="payment_phone"
                                        id="payment_phone"
                                        class="form-control"
                                        placeholder="Enter the number used for payment"
                                        value="{{ old('payment_phone') }}"
                                    >
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- RIGHT SIDE --}}
                    <div class="col-12 col-lg-5">
                        <div class="checkout-card order-summary">

                            <h2 class="section-title">Order Summary</h2>

                            @foreach($cart as $item)
                                @php
                                    $rawImg = $item['image'] ?? ($item['images'][0] ?? null);
                                    $itemImgUrl = null;
                                    if ($rawImg) {
                                        $itemImgUrl = str_starts_with($rawImg, 'http') ? $rawImg : asset('storage/' . $rawImg);
                                    }
                                @endphp

                                <div class="cart-item">
                                    <div class="d-flex justify-content-between gap-3 align-items-center">
                                        <div class="d-flex gap-3 align-items-center">
                                            @if($itemImgUrl)
                                                <img src="{{ $itemImgUrl }}" alt="{{ $item['name'] }}" class="cart-item-img">
                                            @else
                                                <div class="cart-item-img d-flex align-items-center justify-content-center text-muted" style="font-size:10px;">No Img</div>
                                            @endif

                                            <div>
                                                <div class="cart-item-name">
                                                    {{ $item['name'] }}
                                                </div>
                                                <div class="cart-item-meta">
                                                    Size: {{ $item['size'] ?? 'N/A' }} | Color: {{ $item['color'] ?? 'N/A' }} <br>
                                                    Qty: {{ $item['quantity'] }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cart-item-price">
                                            ৳{{ number_format($item['price'] * $item['quantity'], 0) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-4">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>৳{{ number_format($total, 0) }}</span>
                                </div>

                                <div class="summary-row">
                                    <span>Delivery</span>
                                    <span>Calculated at checkout</span>
                                </div>

                                <div class="summary-total">
                                    <span>Total</span>
                                    <span>৳{{ number_format($total, 0) }}</span>
                                </div>

                                {{-- Place Order Button --}}
                                <button
                                    type="submit"
                                    class="place-order-button"
                                    id="placeOrderButton"
                                >
                                    PLACE ORDER
                                </button>

                                <a href="{{ route('cart.index') }}" class="back-cart">
                                    ← Back to Cart
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')

    <script>
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const mobilePaymentSection = document.getElementById('mobilePaymentSection');
        const transactionIdInput = document.getElementById('transaction_id');
        const paymentPhoneInput = document.getElementById('payment_phone');
        const placeOrderButton = document.getElementById('placeOrderButton');

        function checkPaymentMethod() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            if (selectedPayment && (selectedPayment.value === 'bkash' || selectedPayment.value === 'nagad')) {
                mobilePaymentSection.style.display = 'block';
                transactionIdInput.required = true;
                paymentPhoneInput.required = true;
                checkPaymentFields();
            } else {
                mobilePaymentSection.style.display = 'none';
                transactionIdInput.required = false;
                paymentPhoneInput.required = false;
                transactionIdInput.value = '';
                paymentPhoneInput.value = '';
                placeOrderButton.disabled = false;
            }
        }

        function checkPaymentFields() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            if (selectedPayment && selectedPayment.value === 'cod') {
                placeOrderButton.disabled = false;
                return;
            }

            if (selectedPayment && (selectedPayment.value === 'bkash' || selectedPayment.value === 'nagad')) {
                const transactionId = transactionIdInput.value.trim();
                const paymentPhone = paymentPhoneInput.value.trim();

                if (transactionId !== '' && paymentPhone !== '') {
                    placeOrderButton.disabled = false;
                } else {
                    placeOrderButton.disabled = true;
                }
            }
        }

        paymentMethods.forEach(function (method) {
            method.addEventListener('change', checkPaymentMethod);
        });

        transactionIdInput.addEventListener('input', checkPaymentFields);
        paymentPhoneInput.addEventListener('input', checkPaymentFields);

        checkPaymentMethod();
    </script>
</body>
</html>