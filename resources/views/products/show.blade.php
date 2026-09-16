<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $product->name }} - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        body {
            background: #f8f8f8;
            color: #111;
        }

        /* Product Page */

        .product-page {
            padding: 50px 0 80px;
        }

        /* Product Container */

        .product-container {
            max-width: 1200px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Product Image */

        .product-image-box {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .product-image-box img {
            width: 100%;
            height: 560px;
            object-fit: contain;
            display: block;
        }

        /* Thumbnails */

        .product-thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            overflow-x: auto;
        }

        .product-thumbnail {
            width: 75px;
            height: 75px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            cursor: pointer;
            background: #fff;
        }

        .product-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Product Information */

        .product-info-wrapper {
            max-width: 480px;
            margin: 0 auto;
        }

        .product-category {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #777;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 38px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 18px;
        }

        .product-price {
            font-size: 26px;
            font-weight: 700;
        }

        .old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 19px;
            margin-left: 10px;
        }

        .product-description {
            color: #666;
            line-height: 1.7;
            margin-top: 22px;
            margin-bottom: 30px;
        }

        /* Options */

        .option-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .option-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .option-button {
            min-width: 52px;
            height: 44px;
            padding: 0 18px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }

        .option-button:hover {
            border-color: #111;
        }

        .option-button.active {
            background: #111;
            color: #fff;
            border-color: #111;
        }

        .option-button.disabled {
            color: #aaa;
            background: #f5f5f5;
            border-color: #ddd;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* Stock */

        .stock-message {
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 25px;
            color: #555;
        }

        /* Quantity */

        .quantity-wrapper {
            display: flex;
            align-items: center;
            width: 150px;
            height: 46px;
            border: 1px solid #ccc;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
        }

        .quantity-button {
            width: 45px;
            height: 100%;
            border: none;
            background: #f5f5f5;
            font-size: 20px;
            cursor: pointer;
        }

        .quantity-button:hover {
            background: #eaeaea;
        }

        .quantity-input {
            width: 60px;
            height: 100%;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            outline: none;
        }

        /* Add To Cart */

        .add-cart-button {
            width: 100%;
            margin-top: 25px;
            height: 52px;
            border: none;
            border-radius: 6px;
            background: #111;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .5px;
            transition: .2s;
        }

        .add-cart-button:hover:not(:disabled) {
            background: #333;
        }

        .add-cart-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Back Link */

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Mobile */

        @media (max-width: 767px) {

            .product-page {
                padding: 25px 0 50px;
            }

            .product-image-box img {
                height: 380px;
            }

            .product-title {
                font-size: 28px;
                margin-top: 30px;
            }

            .product-price {
                font-size: 23px;
            }

            .product-description {
                font-size: 14px;
            }

            .product-info-wrapper {
                max-width: 100%;
            }

        }

    </style>

</head>


<body>

    @include('layouts.navigation')


    <div class="product-page">

        <div class="product-container">

            {{-- Success Message --}}

            @if(session('success'))

                <div class="alert alert-success mb-4">

                    <div class="fw-semibold mb-3">
                        {{ session('success') }}
                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <a
                            href="{{ route('cart.index') }}"
                            class="btn btn-dark"
                        >
                            VIEW CART
                        </a>

                        <a
                            href="{{ route('checkout.index') }}"
                            class="btn btn-outline-dark"
                        >
                            PROCEED TO CHECKOUT
                        </a>

                    </div>

                </div>

            @endif


            {{-- Error Messages --}}

            @if($errors->any())

                <div class="alert alert-danger mb-4">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="row g-5 align-items-center justify-content-center">


                {{-- PRODUCT IMAGE --}}

                <div class="col-12 col-lg-6">

                    <div class="product-image-box">

                        @if($product->image)

                            <img
                                id="mainProductImage"
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                            >

                        @else

                            <div
                                class="d-flex align-items-center justify-content-center"
                                style="height:560px;"
                            >

                                <span class="text-muted">
                                    No Image Available
                                </span>

                            </div>

                        @endif

                    </div>


                    {{-- Gallery Thumbnails --}}

                    <div class="product-thumbnails">

                        @if($product->image)

                            <div
                                class="product-thumbnail"
                                onclick="changeMainImage('{{ asset('storage/' . $product->image) }}')"
                            >

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                >

                            </div>

                        @endif

                    </div>

                </div>


                {{-- PRODUCT INFORMATION --}}

                <div class="col-12 col-lg-6">

                    <div class="product-info-wrapper">


                        {{-- Category --}}

                        <div class="product-category">

                            {{ $product->category->name ?? 'Collection' }}

                        </div>


                        {{-- Product Name --}}

                        <h1 class="product-title">

                            {{ $product->name }}

                        </h1>


                        {{-- Price --}}

                        <div class="mb-3">

                            @if($product->sale_price)

                                <span class="product-price">

                                    ৳{{ number_format($product->sale_price, 0) }}

                                </span>

                                <span class="old-price">

                                    ৳{{ number_format($product->price, 0) }}

                                </span>

                            @else

                                <span class="product-price">

                                    ৳{{ number_format($product->price, 0) }}

                                </span>

                            @endif

                        </div>


                        {{-- Description --}}

                        @if($product->description)

                            <div class="product-description">

                                {{ $product->description }}

                            </div>

                        @endif


                        <form
                            method="POST"
                            action="{{ route('cart.add', $product->id) }}"
                            id="cartForm"
                        >

                            @csrf


                            {{-- Hidden Variant ID --}}

                            <input
                                type="hidden"
                                name="variant_id"
                                id="variant_id"
                            >


                            {{-- SIZE --}}

                            @php

                                $sizes = $product->variants
                                    ->where('is_active', true)
                                    ->pluck('size')
                                    ->filter()
                                    ->unique()
                                    ->values();

                            @endphp


                            @if($sizes->count())

                                <div>

                                    <div class="option-title">
                                        Select Size
                                    </div>

                                    <div class="option-buttons">

                                        @foreach($sizes as $size)

                                            <button
                                                type="button"
                                                class="option-button size-button"
                                                data-size="{{ $size }}"
                                            >
                                                {{ $size }}
                                            </button>

                                        @endforeach

                                    </div>

                                </div>

                            @endif


                            {{-- COLOR --}}

                            @php

                                $colors = $product->variants
                                    ->where('is_active', true)
                                    ->pluck('color')
                                    ->filter()
                                    ->unique()
                                    ->values();

                            @endphp


                            @if($colors->count())

                                <div>

                                    <div class="option-title">
                                        Select Color
                                    </div>

                                    <div class="option-buttons">

                                        @foreach($colors as $color)

                                            <button
                                                type="button"
                                                class="option-button color-button"
                                                data-color="{{ $color }}"
                                            >
                                                {{ $color }}
                                            </button>

                                        @endforeach

                                    </div>

                                </div>

                            @endif


                            {{-- STOCK --}}

                            <div
                                id="stockMessage"
                                class="stock-message"
                            >
                                Please select your options.
                            </div>


                            {{-- QUANTITY --}}

                            <div class="option-title">
                                Quantity
                            </div>

                            <div class="quantity-wrapper">

                                <button
                                    type="button"
                                    class="quantity-button"
                                    id="decreaseQuantity"
                                >
                                    −
                                </button>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="quantity-input"
                                    value="1"
                                    min="1"
                                    disabled
                                >

                                <button
                                    type="button"
                                    class="quantity-button"
                                    id="increaseQuantity"
                                >
                                    +
                                </button>

                            </div>


                            {{-- ADD TO CART --}}

                            <button
                                type="submit"
                                id="addToCartButton"
                                class="add-cart-button"
                                disabled
                            >
                                ADD TO CART
                            </button>


                        </form>


                        {{-- Continue Shopping --}}

                        <a
                            href="{{ route('home') }}"
                            class="back-link"
                        >
                            ← Continue Shopping
                        </a>


                    </div>

                </div>

            </div>

        </div>

    </div>


    @include('layouts.footer')


    <script>

        const variants = @json(
            $product->variants
                ->where('is_active', true)
                ->values()
        );

        let selectedSize = null;
        let selectedColor = null;
        let selectedVariant = null;


        const sizeButtons =
            document.querySelectorAll('.size-button');

        const colorButtons =
            document.querySelectorAll('.color-button');

        const variantInput =
            document.getElementById('variant_id');

        const quantityInput =
            document.getElementById('quantity');

        const stockMessage =
            document.getElementById('stockMessage');

        const addToCartButton =
            document.getElementById('addToCartButton');


        /* SIZE BUTTON */

        sizeButtons.forEach(button => {

            button.addEventListener('click', function () {

                if (this.classList.contains('disabled')) {
                    return;
                }

                sizeButtons.forEach(btn => {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                selectedSize = this.dataset.size;

                findVariant();

            });

        });


        /* COLOR BUTTON */

        colorButtons.forEach(button => {

            button.addEventListener('click', function () {

                if (this.classList.contains('disabled')) {
                    return;
                }

                colorButtons.forEach(btn => {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                selectedColor = this.dataset.color;

                findVariant();

            });

        });


        /* FIND MATCHING VARIANT */

        function findVariant() {

            selectedVariant = variants.find(variant => {

                const sizeMatch =
                    !selectedSize ||
                    variant.size === selectedSize;

                const colorMatch =
                    !selectedColor ||
                    variant.color === selectedColor;

                return sizeMatch && colorMatch;

            });


            /* No valid variant */

            if (!selectedVariant) {

                variantInput.value = '';

                quantityInput.disabled = true;

                addToCartButton.disabled = true;

                stockMessage.innerText =
                    'Please select a valid combination.';

                return;

            }


            /* Out of stock */

            if (selectedVariant.stock <= 0) {

                variantInput.value = '';

                quantityInput.disabled = true;

                addToCartButton.disabled = true;

                stockMessage.innerText =
                    'Out of stock.';

                return;

            }


            /* Valid variant */

            variantInput.value =
                selectedVariant.id;

            quantityInput.disabled = false;

            addToCartButton.disabled = false;

            quantityInput.max =
                selectedVariant.stock;

            quantityInput.value = 1;

            stockMessage.innerText =
                'Available stock: ' +
                selectedVariant.stock;

        }


        /* QUANTITY + */

        document
            .getElementById('increaseQuantity')
            .addEventListener('click', function () {

                if (!selectedVariant) {
                    return;
                }

                let quantity =
                    parseInt(quantityInput.value);

                const max =
                    selectedVariant.stock;

                if (quantity < max) {

                    quantity++;

                    quantityInput.value =
                        quantity;

                }

            });


        /* QUANTITY - */

        document
            .getElementById('decreaseQuantity')
            .addEventListener('click', function () {

                let quantity =
                    parseInt(quantityInput.value);

                if (quantity > 1) {

                    quantity--;

                    quantityInput.value =
                        quantity;

                }

            });


        /* MANUAL QUANTITY INPUT */

        quantityInput.addEventListener('input', function () {

            if (!selectedVariant) {
                return;
            }

            let quantity =
                parseInt(this.value);

            const max =
                selectedVariant.stock;


            if (!quantity || quantity < 1) {

                this.value = 1;

                return;

            }


            if (quantity > max) {

                this.value = max;

            }

        });


        /* MAIN IMAGE */

        function changeMainImage(imageUrl) {

            const mainImage =
                document.getElementById(
                    'mainProductImage'
                );

            if (mainImage) {

                mainImage.src =
                    imageUrl;

            }

        }

    </script>


</body>

</html>