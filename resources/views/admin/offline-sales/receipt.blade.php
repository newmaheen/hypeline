<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $offlineSale->sale_id ?? $offlineSale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            max-width: 380px;
            margin: auto;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px 0;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt-container">
        <div class="text-center">
            <h2 style="margin: 0; letter-spacing: 2px;">HYPELINE</h2>
            <div>Your Premium Fashion Store</div>
            <small>Retail Invoice / Cash Memo</small>
        </div>

        <div class="divider"></div>

        <div>
            <div><strong>Bill No:</strong> #{{ $offlineSale->sale_id ?? $offlineSale->id }}</div>
            <div><strong>Date:</strong> {{ $offlineSale->created_at ? $offlineSale->created_at->format('d/m/Y h:i A') : date('d/m/Y h:i A') }}</div>
            <div><strong>Customer:</strong> {{ $offlineSale->customer_name ?? 'Walk-in Customer' }}</div>
            @if($offlineSale->customer_phone)
                <div><strong>Phone:</strong> {{ $offlineSale->customer_phone }}</div>
            @endif
            <div><strong>Pay Method:</strong> {{ strtoupper($offlineSale->payment_method ?? 'CASH') }}</div>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offlineSale->items as $item)
                    @php
                        // unit_price নেওয়া হচ্ছে
                        $itemPrice = $item->unit_price ?? $item->price ?? 0;
                        $itemTotal = $item->subtotal ?? ($itemPrice * $item->quantity);
                    @endphp
                    <tr>
                        <td colspan="4" class="fw-bold" style="padding-top: 6px;">
                            {{ $item->product_name ?? $item->variant->product->name ?? 'Product' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 8px; color: #555;">
                            {{ $item->variant_name ?? trim(($item->variant->size ?? '') . ' / ' . ($item->variant->color ?? '')) }}
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($itemPrice, 0) }}</td>
                        <td class="text-end fw-bold">{{ number_format($itemTotal, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table>
            @if(!empty($offlineSale->discount) && $offlineSale->discount > 0)
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-end">৳{{ number_format($offlineSale->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount:</td>
                    <td class="text-end">-৳{{ number_format($offlineSale->discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td class="fw-bold" style="font-size: 15px;">Total Amount:</td>
                {{-- মোট টাকার সঠিক কলাম total --}}
                <td class="text-end fw-bold" style="font-size: 16px;">
                    ৳{{ number_format($offlineSale->total ?? $offlineSale->total_amount ?? 0, 2) }}
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 15px; font-size: 12px;">
            Thank you for shopping with us!<br>
            Please keep this receipt for exchanges.
        </div>
    </div>

</body>
</html>