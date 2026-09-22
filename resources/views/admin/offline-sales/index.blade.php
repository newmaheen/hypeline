<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline Sales - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    {{-- Admin Header --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">HYPELINE ADMIN</a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">← Dashboard</a>
                <a href="{{ route('admin.offline-sales.create') }}" class="btn btn-success btn-sm">+ New Offline Sale</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Offline Sales (POS)</h3>
                <p class="text-muted small mb-0">Manage physical, phone, and manual sales with automatic inventory synchronization.</p>
            </div>
            <a href="{{ route('admin.offline-sales.create') }}" class="btn btn-dark btn-sm px-3">
                + New Offline Sale
            </a>
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Sales Summary Cards -->
        @php
            $todayTotal = $summary['today_total'] ?? 0;
            $todayCount = $summary['today_count'] ?? 0;
            $monthTotal = $summary['month_total'] ?? 0;
            $monthCount = $summary['month_count'] ?? 0;
            $lifetimeTotal = $summary['lifetime_total'] ?? 0;
            $lifetimeCount = $summary['lifetime_count'] ?? 0;
            $cancelledCount = $summary['cancelled_count'] ?? 0;
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <span class="text-muted small text-uppercase fw-semibold">Today's Sales</span>
                        <h4 class="fw-bold text-dark mt-2 mb-0">৳{{ number_format($todayTotal, 2) }}</h4>
                        <span class="text-secondary small">{{ $todayCount }} Completed Transactions</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <span class="text-muted small text-uppercase fw-semibold">This Month's Sales</span>
                        <h4 class="fw-bold text-dark mt-2 mb-0">৳{{ number_format($monthTotal, 2) }}</h4>
                        <span class="text-secondary small">{{ $monthCount }} Completed Transactions</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <span class="text-muted small text-uppercase fw-semibold">Total Offline Revenue</span>
                        <h4 class="fw-bold text-dark mt-2 mb-0">৳{{ number_format($lifetimeTotal, 2) }}</h4>
                        <span class="text-secondary small">{{ $lifetimeCount }} Transactions</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <span class="text-muted small text-uppercase fw-semibold">Cancelled Sales</span>
                        <h4 class="fw-bold text-danger mt-2 mb-0">{{ $cancelledCount }}</h4>
                        <span class="text-secondary small">Restored to Inventory</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.offline-sales.index') }}" method="GET" class="row g-2">
                    <div class="col-12 col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Search by Invoice, Customer or Phone..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="">All Payment Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
                        <a href="{{ route('admin.offline-sales.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">↻</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sales Data Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">Invoice #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="ps-3 fw-bold text-dark font-monospace">
                                        #{{ $sale->invoice_number ?? $sale->id }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $sale->customer_name ?? 'Walk-in Customer' }}</div>
                                        @if($sale->customer_phone)
                                            <small class="text-muted">{{ $sale->customer_phone }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $sale->created_at->format('d M, Y h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $sale->items_count ?? $sale->items->count() }} items
                                        </span>
                                    </td>
                                    <td class="fw-bold">৳{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase">
                                            {{ $sale->payment_method ?? 'Cash' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(($sale->status ?? 'completed') === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.offline-sales.show', $sale->id) }}" class="btn btn-outline-dark" title="View Details">
                                                View
                                            </a>
                                            <a href="{{ route('admin.offline-sales.receipt', $sale->id) }}" target="_blank" class="btn btn-outline-dark" title="Print Receipt">
                                                🖨 Print
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        No offline sales recorded yet. Click "New Offline Sale" to create one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($sales->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $sales->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

</body>
</html>