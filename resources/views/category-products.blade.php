<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">


<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>{{ $category->name }} - Hypeline</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #fff;
        color: #111;
    }

    /* CATEGORY HERO */

    .category-hero {
        background: #111;
        color: #fff;
    }

    .category-hero-inner {
        max-width: 1200px;
        width: 100%;
        margin: auto;
        padding: 70px 25px;
    }

    .category-label {
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: #aaa;
        margin-bottom: 15px;
    }

    .category-title {
        font-size: clamp(42px, 6vw, 72px);
        line-height: 1;
        font-weight: 800;
        letter-spacing: -3px;
        margin: 0;
    }

    .category-description {
        max-width: 600px;
        color: #bbb;
        font-size: 16px;
        line-height: 1.7;
        margin-top: 20px;
    }


    /* PRODUCTS SECTION */

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
        box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
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


    /* EMPTY */

    .empty-products {
        text-align: center;
        padding: 70px 20px;
        border: 1px solid #eee;
    }

    .empty-products h4 {
        margin-bottom: 10px;
    }

    .empty-products p {
        color: #777;
        margin: 0;
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

        .product-image-box {
            height: 280px;
        }
    }


    @media (max-width: 575px) {

        .category-hero-inner {
            padding: 55px 20px;
        }

        .category-title {
            font-size: 48px;
            letter-spacing: -2px;
        }

        .category-description {
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
    }
</style>
```

</head>

<body>

```
{{-- NAVBAR --}}
@include('layouts.navigation')


{{-- CATEGORY HERO --}}
<section class="category-hero">

    <div class="category-hero-inner">

        <div class="category-label">
            Hypeline Collection
        </div>

        <h1 class="category-title">
            {{ $category->name }}
        </h1>

        @if($category->description)
            <p class="category-description">
                {{ $category->description }}
            </p>
        @endif

    </div>

</section>


{{-- PRODUCTS --}}
<section class="hypeline-section">

    <div class="section-label">
        {{ $category->name }}
    </div>

    <h2 class="section-title">
        {{ $category->name }} Collection
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


        {{-- PAGINATION --}}
        <div style="margin-top: 40px;">
            {{ $products->links() }}
        </div>


    @else

        <div class="empty-products">

            <h4>
                No products available
            </h4>

            <p>
                There are currently no products in this category.
            </p>

        </div>

    @endif

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
