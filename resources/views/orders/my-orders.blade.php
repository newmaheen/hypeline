<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>My Orders - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        body {
            background: #f8f8f8;
            color: #111;
        }

        .orders-page {
            padding: 45px 0 70px;
        }

        .orders-title {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .orders-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .order-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            margin-bottom: 18px;
            overflow: hidden;
        }

        .order-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .order-id {
            font-size: 17px;
            font-weight: 700;
        }

        .order-date {
            color: #777;
            font-size: 13px;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #f1f1f1;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .order-card-body {
            padding: 20px 24px;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .info-label {
            display: block;
            color: #777;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .info-value {
            font-weight: 600;
        }

        .order-total {
            font-size: 19px;
            font-weight: 700;
        }

        .payment-method {
            text-transform: uppercase;
        }

        .view-order-button {
            min-height: 44px;
            font-size: 13px;
            font-weight: 700;
        }

        .empty-orders {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 55px 25px;
            text-align: center;
        }

        .empty-orders h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-orders p {
            color: #777;
            margin-bottom: 25px;
        }

        @media (max-width: 767px) {

            .orders-page {
                padding: 25px 0 50px;
            }

            .orders-title {
                font-size: 28px;
            }

            .order-card-header {
                padding: 17px 18px;
                align-items: flex-start;
                flex-direction: column;
            }

            .order-card-body {
                padding: 18px;
            }

            .order-info {
                flex-wrap: wrap;
                margin-bottom: 15px;
            }

        }

    </style>

</head>

<body>

    @include('layouts.navigation')


    <div class="orders-page">

        <div class="container">

            <h1 class="orders-title">
                My Orders
            </h1>

            <p class="orders-subtitle">
                View your order history and track your purchases.
            </p>


            @if($orders->count())


                @foreach($orders as $order)

                    <div class="order-card">


                        {{-- Header --}}

                        <div class="order-card-header">

                            <div>

                                <div class="order-id">
                                    Order #{{ $order->order_code }}
                                </div>

                                <div class="order-date">
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                </div>

                            </div>


                            <span class="status-badge">

                                {{ ucfirst($order->status) }}

                            </span>

                        </div>


                        {{-- Body --}}

                        <div class="order-card-body">

                            <div class="order-info">


                                <div>

                                    <span class="info-label">
                                        Payment
                                    </span>

                                    <span class="info-value payment-method">
                                        {{ $order->payment_method }}
                                    </span>

                                </div>


                                <div>

                                    <span class="info-label">
                                        Payment Status
                                    </span>

                                    <span class="info-value">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>

                                </div>


                                <div>

                                    <span class="info-label">
                                        Total
                                    </span>

                                    <span class="order-total">
                                        ৳{{ number_format($order->total_amount, 0) }}
                                    </span>

                                </div>

                            </div>


                            <a
                                href="{{ route('my-orders.show', $order->order_code) }}"
                                class="btn btn-dark view-order-button"
                            >
                                VIEW ORDER
                            </a>

                        </div>

                    </div>

                @endforeach


            @else


                {{-- Empty Orders --}}

                <div class="empty-orders">

                    <h2>
                        No Orders Yet
                    </h2>

                    <p>
                        You have not placed any orders yet.
                        Start shopping and your orders will appear here.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="btn btn-dark px-4"
                    >
                        START SHOPPING
                    </a>

                </div>


            @endif


        </div>

    </div>


    @include('layouts.footer')


</body>

</html>