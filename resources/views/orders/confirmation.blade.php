<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Order Confirmed - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        body {
            background: #f8f8f8;
            color: #111;
        }

        .confirmation-page {
            padding: 50px 0 70px;
        }

        .success-box {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 35px;
            text-align: center;
            margin-bottom: 25px;
        }

        .success-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #111;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            font-weight: 700;
        }

        .success-title {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-text {
            color: #666;
            margin-bottom: 0;
        }

        .order-id-box {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 18px;
            margin-top: 25px;
        }

        .order-id-label {
            display: block;
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .order-id {
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .confirmation-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 28px;
            height: 100%;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 11px 0;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #777;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
        }

        .product-item {
            padding: 16px 0;
            border-bottom: 1px solid #eee;
        }

        .product-item:first-child {
            padding-top: 0;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-name {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .product-meta {
            color: #777;
            font-size: 13px;
            line-height: 1.7;
        }

        .product-subtotal {
            font-weight: 700;
            white-space: nowrap;
        }

        .total-box {
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 18px;
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            font-weight: 700;
        }

        .action-buttons {
            margin-top: 25px;
        }

        .action-buttons .btn {
            min-height: 48px;
            font-weight: 600;
        }

        @media (max-width: 767px) {

            .confirmation-page {
                padding: 25px 0 50px;
            }

            .success-box {
                padding: 25px 20px;
            }

            .success-title {
                font-size: 25px;
            }

            .confirmation-card {
                padding: 20px;
            }

            .info-row {
                flex-direction: column;
                gap: 3px;
            }

            .info-value {
                text-align: left;
            }

        }

    </style>

</head>

<body>

    @include('layouts.navigation')


    <div class="confirmation-page">

        <div class="container">


            {{-- Success Message --}}

            <div class="success-box">

                <div class="success-icon">
                    ✓
                </div>

                <h1 class="success-title">
                    Order Placed Successfully!
                </h1>

                <p class="success-text">
                    Thank you for shopping with Hypeline.
                    Your order has been received.
                </p>

                <div class="order-id-box">

                    <span class="order-id-label">
                        Order ID
                    </span>

                    <span class="order-id">
                        {{ $order->order_code }}
                    </span>

                </div>

            </div>


            <div class="row g-4">


                {{-- Customer / Order Information --}}

                <div class="col-12 col-lg-5">

                    <div class="confirmation-card">

                        <h2 class="section-title">
                            Order Information
                        </h2>


                        <div class="info-row">

                            <span class="info-label">
                                Customer
                            </span>

                            <span class="info-value">
                                {{ $order->customer_name }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Phone
                            </span>

                            <span class="info-value">
                                {{ $order->customer_phone }}
                            </span>

                        </div>


                        @if($order->customer_email)

                            <div class="info-row">

                                <span class="info-label">
                                    Email
                                </span>

                                <span class="info-value">
                                    {{ $order->customer_email }}
                                </span>

                            </div>

                        @endif


                        <div class="info-row">

                            <span class="info-label">
                                Payment
                            </span>

                            <span class="info-value text-uppercase">
                                {{ $order->payment_method }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Payment Status
                            </span>

                            <span class="info-value text-uppercase">
                                {{ $order->payment_status }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Order Status
                            </span>

                            <span class="info-value text-uppercase">
                                {{ $order->status }}
                            </span>

                        </div>


                        <div class="info-row">

                            <span class="info-label">
                                Delivery Address
                            </span>

                            <span class="info-value">
                                {{ $order->shipping_address }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Ordered Products --}}

                <div class="col-12 col-lg-7">

                    <div class="confirmation-card">

                        <h2 class="section-title">
                            Ordered Products
                        </h2>


                        @foreach($order->items as $item)

                            <div class="product-item">

                                <div class="d-flex justify-content-between gap-3">

                                    <div>

                                        <div class="product-name">
                                            {{ $item->product_name }}
                                        </div>

                                        <div class="product-meta">

                                            Size:
                                            {{ $item->size ?? 'N/A' }}

                                            <br>

                                            Color:
                                            {{ $item->color ?? 'N/A' }}

                                            <br>

                                            Price:
                                            ৳{{ number_format($item->price, 0) }}

                                            ×
                                            {{ $item->quantity }}

                                        </div>

                                    </div>


                                    <div class="product-subtotal">

                                        ৳{{ number_format(
                                            $item->subtotal,
                                            0
                                        ) }}

                                    </div>

                                </div>

                            </div>

                        @endforeach


                        <div class="total-box">

                            <span>
                                Total
                            </span>

                            <span>
                                ৳{{ number_format(
                                    $order->total_amount,
                                    0
                                ) }}
                            </span>

                        </div>


                        {{-- Actions --}}

                        <div class="action-buttons">

                            <div class="row g-2">

                                <div class="col-12 col-md-6">

                                    <a
                                        href="{{ route('order.invoice', [
                                            'orderCode' => $order->order_code,
                                            'invoiceToken' => $order->invoice_token,
                                        ]) }}"
                                        class="btn btn-dark w-100"
                                    >
                                        DOWNLOAD INVOICE
                                    </a>

                                </div>


                                @auth

                                    <div class="col-12 col-md-6">

                                        <a
                                            href="{{ route('my-orders') }}"
                                            class="btn btn-outline-dark w-100"
                                        >
                                            MY ORDERS
                                        </a>

                                    </div>

                                @endauth


                                <div class="col-12">

                                    <a
                                        href="{{ route('home') }}"
                                        class="btn btn-outline-secondary w-100"
                                    >
                                        CONTINUE SHOPPING
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    @include('layouts.footer')


</body>

</html>