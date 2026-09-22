<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline Sale #{{ $offlineSale->sale_id ?? $offlineSale->id }} - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    {{-- Admin Header --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">HYPELINE ADMIN</a>
            <a href="{{ route('admin.offline-sales.index') }}" class="btn btn-outline-light btn-sm">← All Offline Sales</a>
        </div>
    </nav>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">Receipt / Invoice #{{ $offlineSale->sale_id ?? $offlineSale->id }}</h1>
                <p class="text-muted mb-0">Date: {{ $offlineSale->created_at ? $offlineSale->created_at->format('d M Y, h:i A') : date('d M Y') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.offline-sales.receipt', $offlineSale->id) }}" target="_blank" class="btn btn-primary">
                    🖨 Print Receipt
                </a>
                @if(($offlineSale->status ?? 'completed') !== 'cancelled')
                    <form method="POST" action="{{ route('admin.offline-sales.cancel', $offlineSale->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Cancelling this sale will restock all items back to inventory. Are you sure?')">
                            Cancel Sale
                        </button>
                    </form>
                @else
                    <span class="badge text-bg-danger fs-6 align-self-center">Cancelled</span>
                @endif
            </div>
        </div>

        <div class="row g-4">
            {{-- Details Card --}}
            <div class="col-12 col-md-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Sale Information</h5>
                        <div class="mb-2">
                            <small class="text-muted d-block">Customer Name</small>
                            <span class="fw-semibold">{{ $offlineSale->customer_name ?? 'Walk-in Customer' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Phone Number</small>
                            <span>{{ $offlineSale->customer_phone ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Payment Method</small>
                            <span class="badge text-bg-dark">{{ strtoupper($offlineSale->payment_method ?? 'Cash') }}</span>
                        </div>
                        @if(!empty($offlineSale->notes))
                            <div class="mt-3">
                                <small class="text-muted d-block">Note</small>
                                <p class="small text-muted mb-0">{{ $offlineSale->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Products Card --}}
            <div class="col-12 col-md-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Items Sold</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offlineSale->items as $item)
                                        @php
                                            // unit_price বা price যেটি পাওয়া যায় সেটি নেওয়া
                                            $itemPrice = $item->unit_price ?? $item->price ?? 0;
                                            $itemSubtotal = $item->subtotal ?? ($itemPrice * $item->quantity);
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">{{ $item->product_name ?? $item->variant->product->name ?? 'Product' }}</span>
                                                <small class="text-muted d-block">[{{ $item->variant_name ?? trim(($item->variant->size ?? '') . ' / ' . ($item->variant->color ?? '')) }}]</small>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">৳{{ number_format($itemPrice, 2) }}</td>
                                            <td class="text-end fw-semibold">৳{{ number_format($itemSubtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @if(!empty($offlineSale->discount) && $offlineSale->discount > 0)
                                        <tr>
                                            <th colspan="3" class="text-end text-muted">Subtotal:</th>
                                            <th class="text-end text-muted">৳{{ number_format($offlineSale->subtotal, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end text-muted">Discount:</th>
                                            <th class="text-end text-danger">-৳{{ number_format($offlineSale->discount, 2) }}</th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-end">Grand Total:</th>
                                        {{-- total_amount এর জায়গায় মাইগ্রেশনের সঠিক কলাম total --}}
                                        <th class="text-end text-success fs-5">৳{{ number_format($offlineSale->total ?? $offlineSale->total_amount ?? 0, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>