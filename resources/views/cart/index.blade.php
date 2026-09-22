<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Shopping Cart - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        body {
            background: #f8f8f8;
            color: #111;
        }

        .cart-page {
            padding: 50px 0 80px;
        }

        .cart-title {
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .cart-alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .cart-item {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .cart-product-image {
            width: 90px;
            height: 90px;
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f5;
            margin-bottom: 12px;
        }

        .cart-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-name {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .cart-meta {
            color: #777;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .cart-price {
            font-weight: 600;
            margin-top: 12px;
        }

        .cart-subtotal {
            font-size: 17px;
            font-weight: 700;
            text-align: right;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            width: 145px;
            height: 42px;
            border: 1px solid #ccc;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
        }

        .quantity-control input {
            width: 55px;
            height: 100%;
            border: none;
            outline: none;
            text-align: center;
            font-weight: 600;
        }

        .quantity-control button {
            width: 45px;
            height: 100%;
            border: none;
            background: #f4f4f4;
            font-size: 18px;
            cursor: pointer;
        }

        .quantity-control button:hover {
            background: #e8e8e8;
        }

        .update-button {
            border: 1px solid #111;
            background: #fff;
            color: #111;
            padding: 9px 15px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .update-button:hover {
            background: #111;
            color: #fff;
        }

        .remove-button {
            border: none;
            background: transparent;
            color: #888;
            font-size: 13px;
            padding: 5px 0;
        }

        .remove-button:hover {
            color: #111;
            text-decoration: underline;
        }

        .clear-cart-button {
            border: 1px solid #111;
            background: #fff;
            color: #111;
            padding: 9px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
        }

        .clear-cart-button:hover {
            background: #111;
            color: #fff;
        }

        .cart-summary {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 12px;
            padding: 28px;
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
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

        .checkout-button {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 6px;
            background: #111;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            margin-top: 22px;
        }

        .checkout-button:hover {
            background: #333;
        }

        .continue-shopping {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .continue-shopping:hover {
            text-decoration: underline;
        }

        .empty-cart {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 12px;
            padding: 70px 20px;
            text-align: center;
        }

        .empty-cart h2 {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #777;
            margin-bottom: 25px;
        }

        .shop-button {
            display: inline-block;
            background: #111;
            color: #fff;
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
        }

        .shop-button:hover {
            background: #333;
            color: #fff;
        }

        @media (max-width: 767px) {

            .cart-page {
                padding: 30px 0 50px;
            }

            .cart-title {
                font-size: 30px;
                margin-bottom: 25px;
            }

            .cart-item {
                padding: 17px;
            }

            .cart-subtotal {
                text-align: left;
                margin-top: 15px;
            }

            .cart-summary {
                position: static;
                margin-top: 20px;
            }

            .clear-cart-button {
                padding: 8px 12px;
                font-size: 12px;
            }

        }

    </style>

</head>

<body>

    @include('layouts.navigation')

    <div class="cart-page">

        <div class="container">

            {{-- CART HEADER --}}

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="cart-title mb-0">
                    Shopping Cart
                </h1>

                @if(count($cart))

                    <form
                        method="POST"
                        action="{{ route('cart.clear') }}"
                        onsubmit="return confirm('Are you sure you want to clear your cart?');"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="clear-cart-button"
                        >
                            CLEAR CART
                        </button>

                    </form>

                @endif

            </div>

            {{-- SUCCESS MESSAGE --}}

            @if(session('success'))

                <div class="alert alert-success cart-alert">

                    {{ session('success') }}

                </div>

            @endif

            {{-- ERROR MESSAGE --}}

            @if($errors->any())

                <div class="alert alert-danger cart-alert">

                    @foreach($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            @endif

            {{-- CART HAS ITEMS --}}

            @if(count($cart))

                <div class="row g-4">

                    {{-- CART ITEMS --}}

                    <div class="col-12 col-lg-8">

                        @foreach($cart as $item)

                            <div class="cart-item">

                                <div class="row align-items-center">

                                    {{-- PRODUCT INFO --}}

                                    <div class="col-12 col-md-5">

                                        @if(!empty($item['image']))

                                            <div class="cart-product-image">

                                                <img
                                                    src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['name'] }}"
                                                >

                                            </div>

                                        @endif

                                        <div class="cart-item-name">

                                            {{ $item['name'] }}

                                        </div>

                                        <div class="cart-meta">

                                            Size:

                                            <strong>
                                                {{ $item['size'] ?? 'N/A' }}
                                            </strong>

                                        </div>

                                        <div class="cart-meta">

                                            Color:

                                            <strong>
                                                {{ $item['color'] ?? 'N/A' }}
                                            </strong>

                                        </div>

                                        <div class="cart-price">

                                            ৳{{ number_format($item['price'], 0) }}

                                        </div>

                                    </div>

                                    {{-- QUANTITY --}}

                                    <div class="col-12 col-md-4 mt-3 mt-md-0">

                                        <form
                                            method="POST"
                                            action="{{ route('cart.update', $item['variant_id']) }}"
                                        >

                                            @csrf

                                            @method('PUT')

                                            <div class="d-flex align-items-center gap-2">

                                                <div class="quantity-control">

                                                    <button
                                                        type="button"
                                                        onclick="decreaseQuantity(this)"
                                                    >
                                                        −
                                                    </button>

                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        value="{{ $item['quantity'] }}"
                                                        min="1"
                                                    >

                                                    <button
                                                        type="button"
                                                        onclick="increaseQuantity(this)"
                                                    >
                                                        +
                                                    </button>

                                                </div>

                                                <button
                                                    type="submit"
                                                    class="update-button"
                                                >
                                                    Update
                                                </button>

                                            </div>

                                        </form>

                                        {{-- REMOVE --}}

                                        <form
                                            method="POST"
                                            action="{{ route('cart.remove', $item['variant_id']) }}"
                                            class="mt-2"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="remove-button"
                                            >
                                                Remove
                                            </button>

                                        </form>

                                    </div>

                                    {{-- SUBTOTAL --}}

                                    <div class="col-12 col-md-3">

                                        <div class="cart-subtotal">

                                            Subtotal
                                            <br>

                                            <span>

                                                ৳{{ number_format($item['price'] * $item['quantity'], 0) }}

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                    {{-- ORDER SUMMARY --}}

                    <div class="col-12 col-lg-4">

                        <div class="cart-summary">

                            <div class="summary-title">

                                Order Summary

                            </div>

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

                            <a
                                href="{{ route('checkout.index') }}"
                                class="btn checkout-button d-flex align-items-center justify-content-center"
                            >
                                PROCEED TO CHECKOUT
                            </a>

                            <a
                                href="{{ route('home') }}"
                                class="continue-shopping"
                            >
                                ← Continue Shopping
                            </a>

                        </div>

                    </div>

                </div>

            @else

                {{-- EMPTY CART --}}

                <div class="empty-cart">

                    <h2>
                        Your cart is empty
                    </h2>

                    <p>
                        Looks like you haven't added anything yet.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="shop-button"
                    >
                        START SHOPPING
                    </a>

                </div>

            @endif

        </div>

    </div>

    <script>

        function decreaseQuantity(button) {

            const input =
                button.parentElement.querySelector('input');

            let value =
                parseInt(input.value);

            if (value > 1) {

                input.value = value - 1;

            }

        }


        function increaseQuantity(button) {

            const input =
                button.parentElement.querySelector('input');

            let value =
                parseInt(input.value);

            input.value = value + 1;

        }

    </script>

</body>

</html>