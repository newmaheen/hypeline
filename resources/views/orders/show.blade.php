<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Order Details - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        body {
            background: #f8f8f8;
            color: #111;
        }

        .order-details-page {
            padding: 45px 0 70px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .details-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 20px;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .order-number {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .order-date {
            color: #777;
            font-size: 13px;
            margin-top: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            background: #111;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 12px 0;
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
            padding: 18px 0;
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
            font-size: 16px;
            margin-bottom: 6px;
        }

        .product-meta {
            color: #777;
            font-size: 13px;
            line-height: 1.8;
        }

        .product-subtotal {
            font-size: 15px;
            font-weight: 700;
            white-space: nowrap;
        }

        .total-row {
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 18px;
            display: flex;
            justify-content: space-between;
            font-size: 21px;
            font-weight: 700;
        }

        .action-buttons {
            margin-top: 25px;
        }

        .action-buttons .btn {
            min-height: 48px;
            font-weight: 600;
        }

        .payment-status {
            text-transform: capitalize;
        }

        @media (max-width: 767px) {

            .order-details-page {
                padding: 25px 0 50px;
            }

            .page-title {
                font-size: 28px;
            }

            .details-card {
                padding: 20px;
            }

            .order-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-value {
                text-align: left;
            }

        }

    </style>

</head>

<body>

    @include('layouts.navigation')


    <div class="order-details-page">

        <div class="container">


            {{-- Page Heading --}}

            <h1 class="page-title">
                Order Details
            </h1>

            <p class="page-subtitle">
                Review your order information and purchased products.
            </p>


            {{-- Order Header --}}

            <div class="details-card">

                <div class="order-header">

                    <div>

                        <div class="order-number">
                            Order #{{ $order->order_code }}
                        </div>

                        <div class="order-date">
                            Placed on
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>


                    <span class="status-badge">

                        {{ ucfirst($order->status) }}

                    </span>

                </div>


                <div class="row g-4">


                    {{-- Payment --}}

                    <div class="col-12 col-md-6">

                        <div class="info-row">

                            <span class="info-label">
                                Payment Method
                            </span>

                            <span class="info-value text-uppercase">
                                {{ $order->payment_method }}
                            </span>

                        </div>

                    </div>


                    {{-- Payment Status --}}

                    <div class="col-12 col-md-6">

                        <div class="info-row">

                            <span class="info-label">
                                Payment Status
                            </span>

                            <span class="info-value payment-status">
                                {{ $order->payment_status }}
                            </span>

                        </div>

                    </div>


                </div>

            </div>


            <div class="row g-4">


                {{-- Customer Information --}}

                <div class="col-12 col-lg-5">

                    <div class="details-card h-100">

                        <h2 class="section-title">
                            Customer Information
                        </h2>


                        <div class="info-row">

                            <span class="info-label">
                                Name
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

                    <div class="details-card">

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


                        <div class="total-row">

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


                        {{-- Action Buttons --}}

                        <div class="action-buttons">

                            <div class="row g-2">


                                @if($order->invoice_token)

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

                                @endif


                                <div class="col-12 col-md-6">

                                    <a
                                        href="{{ route('my-orders') }}"
                                        class="btn btn-outline-dark w-100"
                                    >
                                        BACK TO MY ORDERS
                                    </a>

                                </div>


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


    {{--

    @if($order->status === 'pending' && $order->created_at->addHour()->isFuture())

        <form
            method="POST"
            action="{{ route('my-orders.cancel', $order->order_code) }}"
        >
            @csrf

            <button type="submit">
                Cancel Order
            </button>
        </form>

    @endif

    --}}


</body>

</html>