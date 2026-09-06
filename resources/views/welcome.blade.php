<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Hypeline | Quality. Style. Trust.</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            color: #111;
        }

        /* HERO */

        .hypeline-hero {
            background: #111;
            color: #fff;
            min-height: 520px;
            display: flex;
            align-items: center;
        }

        .hero-inner {
            max-width: 1200px;
            width: 100%;
            margin: auto;
            padding: 70px 25px;
        }

        .hero-small {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: clamp(48px, 7vw, 90px);
            line-height: .95;
            font-weight: 800;
            letter-spacing: -4px;
            margin: 0 0 25px;
        }

        .hero-description {
            max-width: 520px;
            color: #bbb;
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .hero-button {
            display: inline-block;
            background: #fff;
            color: #111;
            padding: 14px 28px;
            border-radius: 3px;
            text-decoration: none;
            font-weight: 700;
            transition: .2s;
        }

        .hero-button:hover {
            background: #ddd;
            color: #111;
        }


        /* SECTION */

        .hypeline-section {
            max-width: 1200px;
            margin: auto;
            padding: 80px 25px;
        }

        .section-label {
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #888;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: 40px;
        }


        /* PRODUCT GRID */

        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            transition: .25s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        .product-image-box {
            width: 100%;
            height: 310px;
            background: #f5f5f5;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.03);
        }

        .no-image {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #aaa;
            font-size: 14px;
        }

        .product-info {
            padding: 20px;
        }

        .product-category {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #888;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .price {
            font-size: 17px;
            font-weight: 700;
        }

        .old-price {
            color: #999;
            text-decoration: line-through;
            margin-right: 8px;
            font-size: 14px;
        }

        .view-product {
            display: block;
            margin-top: 18px;
            width: 100%;
            text-align: center;
            padding: 11px;
            border: 1px solid #111;
            color: #111;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .view-product:hover {
            background: #111;
            color: #fff;
        }


        /* FEATURES */

        .features {
            background: #f7f7f7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature {
            background: #fff;
            padding: 35px;
            border: 1px solid #eee;
        }

        .feature-number {
            font-size: 13px;
            color: #999;
            margin-bottom: 20px;
        }

        .feature h3 {
            font-size: 20px;
            margin-bottom: 12px;
        }

        .feature p {
            color: #777;
            line-height: 1.7;
            margin: 0;
        }


        /* CTA */

        .cta {
            background: #111;
            color: #fff;
            text-align: center;
        }

        .cta-inner {
            max-width: 800px;
            margin: auto;
            padding: 80px 25px;
        }

        .cta h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .cta p {
            color: #aaa;
            margin-bottom: 30px;
        }


        /* FOOTER */

        .hypeline-footer {
            background: #111;
            border-top: 1px solid #333;
            color: #aaa;
            padding: 25px;
            text-align: center;
            font-size: 13px;
        }

        .hypeline-footer strong {
            color: #fff;
            letter-spacing: 2px;
        }


        /* MOBILE */

        @media (max-width: 991px) {

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .product-image-box {
                height: 280px;
            }

        }


        @media (max-width: 575px) {

            .hypeline-hero {
                min-height: 470px;
            }

            .hero-inner {
                padding: 55px 20px;
            }

            .hero-title {
                font-size: 52px;
                letter-spacing: -2px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hypeline-section {
                padding: 60px 18px;
            }

            .section-title {
                font-size: 30px;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-image-box {
                height: 210px;
            }

            .product-info {
                padding: 14px;
            }

            .product-name {
                font-size: 14px;
            }

            .price {
                font-size: 15px;
            }

            .view-product {
                font-size: 12px;
                padding: 9px;
            }

            .feature {
                padding: 25px;
            }

            .cta h2 {
                font-size: 32px;
            }

        }

    </style>

</head>


<body>


{{-- EXISTING NAVBAR — UNCHANGED --}}

@include('layouts.navigation')


{{-- HERO --}}

<section class="hypeline-hero">

    <div class="hero-inner">

        <div class="hero-small">
            Hypeline
        </div>

        <h1 class="hero-title">
            STYLE<br>
            THAT<br>
            SPEAKS.
        </h1>

        <p class="hero-description">
            Discover jerseys and fashion essentials
            selected for people who care about quality,
            style and individuality.
        </p>

        <a
            href="#collection"
            class="hero-button"
        >
            SHOP COLLECTION
        </a>

    </div>

</section>



{{-- PRODUCTS --}}

<section
    id="collection"
    class="hypeline-section"
>

    <div class="section-label">
        Shop Hypeline
    </div>

    <h2 class="section-title">
        Latest Collection
    </h2>


    @if($products->count())

        <div class="products-grid">

            @foreach($products as $product)

                <div class="product-card">


                    {{-- IMAGE --}}

                    <div class="product-image-box">

                        @if($product->image)

                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="product-image"
                            >

                        @else

                            <div class="no-image">
                                No Image
                            </div>

                        @endif

                    </div>


                    {{-- INFO --}}

                    <div class="product-info">

                        <div class="product-category">
                            {{ $product->category->name ?? 'Collection' }}
                        </div>


                        <div class="product-name">
                            {{ $product->name }}
                        </div>


                        @if($product->sale_price)

                            <div class="price">

                                <span class="old-price">
                                    ৳{{ number_format($product->price, 0) }}
                                </span>

                                ৳{{ number_format($product->sale_price, 0) }}

                            </div>

                        @else

                            <div class="price">
                                ৳{{ number_format($product->price, 0) }}
                            </div>

                        @endif


                        <a
                            href="{{ route('products.show', $product->slug) }}"
                            class="view-product"
                        >
                            VIEW PRODUCT
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="text-center py-5">

            <h4>
                No products available
            </h4>

            <p class="text-muted">
                New products are coming soon.
            </p>

        </div>

    @endif

</section>



{{-- FEATURES --}}

<section class="features">

    <div class="hypeline-section">

        <div class="section-label">
            Why Hypeline
        </div>

        <h2 class="section-title">
            Quality comes first.
        </h2>


        <div class="features-grid">


            <div class="feature">

                <div class="feature-number">
                    01
                </div>

                <h3>
                    Quality
                </h3>

                <p>
                    We focus on products that look good,
                    feel good and deliver reliable quality.
                </p>

            </div>


            <div class="feature">

                <div class="feature-number">
                    02
                </div>

                <h3>
                    Style
                </h3>

                <p>
                    Modern designs and carefully selected
                    collections for your everyday style.
                </p>

            </div>


            <div class="feature">

                <div class="feature-number">
                    03
                </div>

                <h3>
                    Trust
                </h3>

                <p>
                    Straightforward ordering, clear pricing
                    and dependable customer service.
                </p>

            </div>


        </div>

    </div>

</section>



{{-- CTA --}}

<section class="cta">

    <div class="cta-inner">

        <h2>
            Find your style.
        </h2>

        <p>
            Explore the latest products from Hypeline.
        </p>

        <a
            href="#collection"
            class="hero-button"
        >
            EXPLORE NOW
        </a>

    </div>

</section>



{{-- FOOTER --}}

<footer class="hypeline-footer">

    <strong>HYPELINE</strong>

    <span>
        &nbsp; Quality. Style. Trust.
    </span>

    <br><br>

    © {{ date('Y') }} Hypeline. All rights reserved.

</footer>


</body>

</html>