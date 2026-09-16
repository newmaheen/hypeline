<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

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

            <h1 class="checkout-title">
                Checkout
            </h1>


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


            <form
                method="POST"
                action="{{ route('checkout.store') }}"
            >

                @csrf


                <div class="row g-4">


                    {{-- LEFT SIDE --}}

                    <div class="col-12 col-lg-7">

                        <div class="checkout-card">


                            {{-- Customer Information --}}

                            <h2 class="section-title">
                                Customer Information
                            </h2>


                            <div class="mb-3">

                                <label class="form-label">
                                    Name
                                </label>

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

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    name="customer_phone"
                                    class="form-control"
                                    placeholder="Enter your phone number"
                                    value="{{ old('customer_phone') }}"
                                    required
                                >

                            </div>


                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                    <span class="text-muted">
                                        (Optional)
                                    </span>

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

                                <label class="form-label">
                                    Shipping Address
                                </label>

                                <textarea
                                    name="shipping_address"
                                    class="form-control"
                                    placeholder="Enter your full delivery address"
                                    required
                                >{{ old('shipping_address') }}</textarea>

                            </div>


                            <hr class="my-4">


                            {{-- Payment --}}

                            <h2 class="section-title">
                                Payment Method
                            </h2>


                            {{-- COD --}}

                            <label class="payment-option d-block">

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="cod"
                                    checked
                                >

                                <strong>
                                    Cash on Delivery
                                </strong>

                            </label>


                            {{-- bKash --}}

                            <label class="payment-option d-block">

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="bkash"
                                >

                                <strong>
                                    bKash
                                </strong>

                            </label>


                            {{-- Nagad --}}

                            <label class="payment-option d-block">

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="nagad"
                                >

                                <strong>
                                    Nagad
                                </strong>

                            </label>


                            {{-- Mobile Payment --}}

                            <div
                                id="mobilePaymentSection"
                                class="mobile-payment-box"
                                style="display: none;"
                            >

                                <p class="mb-3">

                                    Send payment to:

                                    <strong>
                                        01XXXXXXXXX
                                    </strong>

                                </p>


                                {{-- Transaction ID --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Transaction ID
                                    </label>

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

                                    <label class="form-label">
                                        Payment Phone Number
                                    </label>

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


                            <h2 class="section-title">
                                Order Summary
                            </h2>


                            @foreach($cart as $item)

                                <div class="cart-item">

                                    <div class="d-flex justify-content-between gap-3">

                                        <div>

                                            <div class="cart-item-name">
                                                {{ $item['name'] }}
                                            </div>

                                            <div class="cart-item-meta">

                                                Size:
                                                {{ $item['size'] ?? 'N/A' }}

                                                <br>

                                                Color:
                                                {{ $item['color'] ?? 'N/A' }}

                                                <br>

                                                Quantity:
                                                {{ $item['quantity'] }}

                                            </div>

                                        </div>


                                        <div class="cart-item-price">

                                            ৳{{ number_format(
                                                $item['price'] * $item['quantity'],
                                                0
                                            ) }}

                                        </div>

                                    </div>

                                </div>

                            @endforeach


                            <div class="mt-4">


                                <div class="summary-row">

                                    <span>
                                        Subtotal
                                    </span>

                                    <span>
                                        ৳{{ number_format($total, 0) }}
                                    </span>

                                </div>


                                <div class="summary-row">

                                    <span>
                                        Delivery
                                    </span>

                                    <span>
                                        Calculated at checkout
                                    </span>

                                </div>


                                <div class="summary-total">

                                    <span>
                                        Total
                                    </span>

                                    <span>
                                        ৳{{ number_format($total, 0) }}
                                    </span>

                                </div>


                                {{-- Place Order Button --}}

                                <button
                                    type="submit"
                                    class="place-order-button"
                                    id="placeOrderButton"
                                >
                                    PLACE ORDER
                                </button>


                                <a
                                    href="{{ route('cart.index') }}"
                                    class="back-cart"
                                >
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

        const paymentMethods =
            document.querySelectorAll(
                'input[name="payment_method"]'
            );


        const mobilePaymentSection =
            document.getElementById(
                'mobilePaymentSection'
            );


        const transactionIdInput =
            document.getElementById(
                'transaction_id'
            );


        const paymentPhoneInput =
            document.getElementById(
                'payment_phone'
            );


        const placeOrderButton =
            document.getElementById(
                'placeOrderButton'
            );


        /*
        |--------------------------------------------------------------------------
        | Check Payment Method
        |--------------------------------------------------------------------------
        */

        function checkPaymentMethod() {

            const selectedPayment =
                document.querySelector(
                    'input[name="payment_method"]:checked'
                );


            /*
            |--------------------------------------------------------------------------
            | bKash / Nagad
            |--------------------------------------------------------------------------
            */

            if (
                selectedPayment &&
                (
                    selectedPayment.value === 'bkash' ||
                    selectedPayment.value === 'nagad'
                )
            ) {

                // Show mobile payment section
                mobilePaymentSection.style.display =
                    'block';


                // Make fields required
                transactionIdInput.required = true;

                paymentPhoneInput.required = true;


                // Check fields
                checkPaymentFields();

            }


            /*
            |--------------------------------------------------------------------------
            | COD
            |--------------------------------------------------------------------------
            */

            else {

                // Hide mobile payment section
                mobilePaymentSection.style.display =
                    'none';


                // Remove required
                transactionIdInput.required = false;

                paymentPhoneInput.required = false;


                // Clear mobile payment fields
                transactionIdInput.value = '';

                paymentPhoneInput.value = '';


                // COD = Button enabled
                placeOrderButton.disabled = false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Check bKash / Nagad Fields
        |--------------------------------------------------------------------------
        */

        function checkPaymentFields() {

            const selectedPayment =
                document.querySelector(
                    'input[name="payment_method"]:checked'
                );


            /*
            |--------------------------------------------------------------------------
            | COD
            |--------------------------------------------------------------------------
            */

            if (
                selectedPayment &&
                selectedPayment.value === 'cod'
            ) {

                placeOrderButton.disabled = false;

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | bKash / Nagad
            |--------------------------------------------------------------------------
            */

            if (
                selectedPayment &&
                (
                    selectedPayment.value === 'bkash' ||
                    selectedPayment.value === 'nagad'
                )
            ) {

                const transactionId =
                    transactionIdInput.value.trim();


                const paymentPhone =
                    paymentPhoneInput.value.trim();


                /*
                |--------------------------------------------------------------------------
                | Both fields filled
                |--------------------------------------------------------------------------
                */

                if (
                    transactionId !== '' &&
                    paymentPhone !== ''
                ) {

                    placeOrderButton.disabled = false;

                }


                /*
                |--------------------------------------------------------------------------
                | One or both fields empty
                |--------------------------------------------------------------------------
                */

                else {

                    placeOrderButton.disabled = true;

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Payment Method Change
        |--------------------------------------------------------------------------
        */

        paymentMethods.forEach(function (method) {

            method.addEventListener(
                'change',
                checkPaymentMethod
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Transaction ID Input
        |--------------------------------------------------------------------------
        */

        transactionIdInput.addEventListener(
            'input',
            checkPaymentFields
        );


        /*
        |--------------------------------------------------------------------------
        | Payment Phone Input
        |--------------------------------------------------------------------------
        */

        paymentPhoneInput.addEventListener(
            'input',
            checkPaymentFields
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Check
        |--------------------------------------------------------------------------
        */

        checkPaymentMethod();

    </script>


</body>

</html>

