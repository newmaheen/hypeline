@extends('admin.layouts.app') {{-- Update this line if your admin layout file has a different path --}}

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Offline Sales</h3>
            <p class="text-muted small mb-0">Manage physical, phone, and manual sales with automatic inventory synchronization.</p>
        </div>
        <a href="{{ route('admin.offline-sales.create') }}" class="btn btn-dark btn-sm rounded-1 px-3">
            <i class="bi bi-plus-lg me-1"></i> New Offline Sale
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-1" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-1" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Sales Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body p-3">
                    <span class="text-muted small text-uppercase fw-semibold">Today's Sales</span>
                    <h4 class="fw-bold text-dark mt-2 mb-0">৳{{ number_format($summary['today_total'] ?? 0, 2) }}</h4>
                    <span class="text-secondary small">{{ $summary['today_count'] ?? 0 }} Completed Transactions</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body p-3">
                    <span class="text-muted small text-uppercase fw-semibold">This Month's Sales</span>
                    <h4 class="fw-bold text-dark mt-2 mb-0">৳{{ number_format($summary['month_total'] ?? 0, 2) }}</h4>
                    <span class="text-secondary small">{{ $summary['month_count'] ?? 0 }} Completed Transactions</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body p-3">
                    <span class="text-muted small text-uppercase fw-semibold">Total Offline Revenue</span>
                    <h4 class="fw-bold text-dark mt-2 mb-0">৳{{ number_format($summary['lifetime_total'] ?? 0, 2) }}</h4>
                    <span class="text-secondary small">{{ $summary['lifetime_count'] ?? 0 }} Transactions</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-1">
                <div class="card-body p-3">
                    <span class="text-muted small text-uppercase fw-semibold">Cancelled Sales</span>
                    <h4 class="fw-bold text-danger mt-2 mb-0">{{ $summary['cancelled_count'] ?? 0 }}</h4>
                    <span class="text-secondary small">Restored to Inventory</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-1 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.offline-sales.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm rounded-1" 
                           placeholder="Search by Sale ID, Customer Name or Phone..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="payment_method" class="form-select form-select-sm rounded-1">
                        <option value="">All Payment Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                        <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="status" class="form-select form-select-sm rounded-1">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <input type="date" name="date" class="form-control form-control-sm rounded-1" value="{{ request('date') }}">
                </div>
                <div class="col-12 col-sm-6 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-dark btn-sm rounded-1 w-100">Filter</button>
                    <a href="{{ route('admin.offline-sales.index') }}" class="btn btn-outline-secondary btn-sm rounded-1" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Data Table -->
    <div class="card border-0 shadow-sm rounded-1">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Sale ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items Count</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">{{ $sale->sale_id }}</td>
                                <td>
                                    <div>{{ $sale->customer_name ?? 'N/A' }}</div>
                                    @if($sale->customer_phone)
                                        <small class="text-muted">{{ $sale->customer_phone }}</small>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $sale->items_count ?? $sale->items->count() }} items</span></td>
                                <td class="fw-bold">৳{{ number_format($sale->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-secondary text-uppercase" style="font-size: 0.75rem;">
                                        {{ $sale->payment_method }}
                                    </span>
                                </td>
                                <td>
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $sale->admin->name ?? 'Admin' }}</small></td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.offline-sales.show', $sale->id) }}" class="btn btn-outline-dark" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.offline-sales.print', $sale->id) }}" target="_blank" class="btn btn-outline-dark" title="Print Receipt">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No offline sales recorded yet.
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
@endsection