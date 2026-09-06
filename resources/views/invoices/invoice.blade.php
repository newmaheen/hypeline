<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Invoice - {{ $order->order_code }}
    </title>

    <style>

        @page {
            margin: 35px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo {
            width: 95px;
            height: 95px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .tagline {
            font-size: 10px;
            letter-spacing: 2px;
            color: #555;
            margin-top: 3px;
        }

        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        .info-title {
            background: #111;
            color: #fff;
            font-weight: bold;
            padding: 8px;
            margin-bottom: 0;
        }

        .label {
            font-weight: bold;
        }

        .products-title {
            background: #111;
            color: #fff;
            font-weight: bold;
            padding: 8px;
            margin-top: 20px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table th {
            background: #f1f1f1;
            border: 1px solid #111;
            padding: 8px;
            font-size: 11px;
        }

        .products-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            width: 100%;
            margin-top: 15px;
        }

        .total {
            text-align: right;
            font-size: 17px;
            font-weight: bold;
            border-top: 2px solid #111;
            padding-top: 10px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

    </style>

</head>

<body>

    {{-- HEADER --}}

    <div class="header">

        <img
            src="{{ public_path('images/hypeline-logo.png') }}"
            class="logo"
        >

        <div class="company-name">
            HYPELINE
        </div>

        <div class="tagline">
            QUALITY &nbsp; • &nbsp; TRUST &nbsp; • &nbsp; STYLE
        </div>

    </div>


    <div class="invoice-title">
        ORDER INVOICE
    </div>


    {{-- ORDER + CUSTOMER INFORMATION --}}

    <table class="info-table">

        <tr>

            <td width="50%">

                <div class="info-title">
                    Order Information
                </div>

                <br>

                <span class="label">
                    Order ID:
                </span>

                {{ $order->order_code }}

                <br>

                <span class="label">
                    Order Date:
                </span>

                {{ $order->created_at->format('d M Y, h:i A') }}

                <br>

                <span class="label">
                    Order Status:
                </span>

                {{ ucfirst($order->status) }}

            </td>


            <td width="50%">

                <div class="info-title">
                    Customer Information
                </div>

                <br>

                <span class="label">
                    Name:
                </span>

                {{ $order->customer_name }}

                <br>

                <span class="label">
                    Phone:
                </span>

                {{ $order->customer_phone }}

                <br>

                <span class="label">
                    Email:
                </span>

                {{ $order->customer_email ?? 'N/A' }}

                <br>

                <span class="label">
                    Shipping Address:
                </span>

                {{ $order->shipping_address }}

            </td>

        </tr>

    </table>


    {{-- PAYMENT INFORMATION --}}

    <table class="info-table">

        <tr>

            <td>

                <div class="info-title">
                    Payment Information
                </div>

                <br>

                <span class="label">
                    Payment Method:
                </span>

                {{ strtoupper($order->payment_method) }}

                &nbsp;&nbsp;&nbsp;

                <span class="label">
                    Payment Status:
                </span>

                {{ ucfirst($order->payment_status) }}

                &nbsp;&nbsp;&nbsp;

                <span class="label">
                    Transaction ID:
                </span>

                {{ $order->transaction_id ?? 'N/A' }}

            </td>

        </tr>

    </table>


    {{-- PRODUCTS --}}

    <div class="products-title">
        ORDERED PRODUCTS
    </div>

    <table class="products-table">

        <thead>

            <tr>

                <th>
                    Product
                </th>

                <th class="text-center">
                    Size
                </th>

                <th class="text-center">
                    Color
                </th>

                <th class="text-right">
                    Price
                </th>

                <th class="text-center">
                    Qty
                </th>

                <th class="text-right">
                    Subtotal
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach($order->items as $item)

                <tr>

                    <td>
                        {{ $item->product_name }}
                    </td>

                    <td class="text-center">
                        {{ $item->size ?? 'N/A' }}
                    </td>

                    <td class="text-center">
                        {{ $item->color ?? 'N/A' }}
                    </td>

                    <td class="text-right">
                        ৳{{ number_format($item->price, 2) }}
                    </td>

                    <td class="text-center">
                        {{ $item->quantity }}
                    </td>

                    <td class="text-right">
                        ৳{{ number_format($item->subtotal, 2) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    {{-- TOTAL --}}

    <div class="total-box">

        <div class="total">

            Total Amount:
            ৳{{ number_format($order->total_amount, 2) }}

        </div>

    </div>


    {{-- FOOTER --}}

    <div class="footer">

        <strong>
            Thank you for shopping with HYPELINE.
        </strong>

        <br>

        Quality • Trust • Style

        <br><br>

        www.hypelineshopbd.com
        &nbsp; | &nbsp;
        hypelinebd@gmail.com
        &nbsp; | &nbsp;
        +8801348-061290

    </div>


</body>

</html>