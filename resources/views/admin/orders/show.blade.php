<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    {{-- Admin Header --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                HYPELINE ADMIN
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light btn-sm">
                ← Orders
            </a>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="container-fluid px-4 py-4">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Heading --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Order #{{ $order->order_code }}
                </h1>
                <p class="text-muted mb-0">
                    {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') : 'N/A' }}
                </p>
            </div>

            {{-- Current Status --}}
            <div>
                @switch($order->status)
                    @case('pending')
                        <span class="badge text-bg-warning fs-6">Pending</span>
                        @break
                    @case('confirmed')
                        <span class="badge text-bg-primary fs-6">Confirmed</span>
                        @break
                    @case('processing')
                        <span class="badge text-bg-info fs-6">Processing</span>
                        @break
                    @case('shipped')
                        <span class="badge text-bg-dark fs-6">Shipped</span>
                        @break
                    @case('delivered')
                        <span class="badge text-bg-success fs-6">Delivered</span>
                        @break
                    @case('cancelled')
                        <span class="badge text-bg-danger fs-6">Cancelled</span>
                        @break
                    @default
                        <span class="badge text-bg-secondary fs-6">{{ ucfirst($order->status) }}</span>
                @endswitch
            </div>
        </div>

        {{-- Order Status Update --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Update Order Status</h5>
                    <form method="POST" action="{{ route('admin.orders.update-status', $order->order_code ?? $order->id) }}" class="row g-3 align-items-end">
                            @csrf
                            @method('PUT')

                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="status" class="form-label fw-semibold">Order Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-auto">
                        <button type="submit" class="btn btn-dark">Update Status</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">

            {{-- Customer Information --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Customer Information</h5>

                        <div class="mb-3">
                            <small class="text-muted d-block">Name</small>
                            <span class="fw-semibold">{{ $order->customer_name }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Phone</small>
                            <span class="fw-semibold">{{ $order->customer_phone }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Email</small>
                            <span>{{ $order->customer_email ?? 'N/A' }}</span>
                        </div>

                        <div>
                            <small class="text-muted d-block">Shipping Address</small>
                            <span>{{ $order->shipping_address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Payment Information</h5>

                        <div class="mb-3">
                            <small class="text-muted d-block">Payment Method</small>
                            <span class="badge text-bg-dark">{{ strtoupper($order->payment_method) }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Payment Status</small>
                            @if($order->payment_status === 'paid')
                                <span class="badge text-bg-success">Paid</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge text-bg-warning">Pending</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="badge text-bg-danger">Failed / Declined</span>
                            @else
                                <span class="badge text-bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Transaction ID</small>
                            <span class="fw-semibold font-monospace">{{ $order->transaction_id ?? 'N/A' }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Payment Phone Number</small>
                            <span class="fw-semibold">{{ $order->payment_phone ?? 'N/A' }}</span>
                        </div>

                        {{-- Approve / Decline Verification Action Buttons --}}
                        @if(
                            $order->payment_method !== 'cod' &&
                            $order->payment_status === 'pending' &&
                            $order->status !== 'cancelled'
                        )
                            <div class="border-top pt-3 mt-3">
                                <small class="text-muted d-block mb-2">Verify Customer Payment:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    {{-- Approve Button --}}
                                    <form method="POST" action="{{ route('admin.orders.approve-payment', $order->order_code) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this payment?')">
                                            ✓ Approve Payment
                                        </button>
                                    </form>

                                    {{-- Decline Button --}}
                                    <form method="POST" action="{{ route('admin.orders.decline-payment', $order->order_code) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to decline this payment? The order will be cancelled and the products will be returned to stock.')">
                                            ✕ Decline Payment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- Ordered Products --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Ordered Products</h5>

                @foreach($order->items as $item)
                    <div class="border rounded p-3 mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-5">
                                <h6 class="fw-bold mb-2">{{ $item->product_name }}</h6>
                                <div class="text-muted small">
                                    <span class="me-3">Size: <strong>{{ $item->size ?? 'N/A' }}</strong></span>
                                    <span>Color: <strong>{{ $item->color ?? 'N/A' }}</strong></span>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <small class="text-muted d-block">Price</small>
                                <span class="fw-semibold">৳{{ number_format($item->price, 2) }}</span>
                            </div>

                            <div class="col-6 col-md-2">
                                <small class="text-muted d-block">Quantity</small>
                                <span class="fw-semibold">{{ $item->quantity }}</span>
                            </div>

                            <div class="col-12 col-md-3 text-md-end">
                                <small class="text-muted d-block">Subtotal</small>
                                <span class="fw-bold">৳{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Total --}}
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Order Total</h5>
                        <h4 class="fw-bold mb-0">৳{{ number_format($order->total_amount, 2) }}</h4>
                    </div>
                </div>

            </div>
        </div>

        {{-- Back --}}
        <div class="mt-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                ← Back to Orders
            </a>
        </div>

    </div>

    {{-- Footer --}}
    <footer class="bg-dark text-white mt-5">
        <div class="container py-4 text-center">
            <p class="mb-0 text-white-50 small">
                © {{ date('Y') }} HYPELINE. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>