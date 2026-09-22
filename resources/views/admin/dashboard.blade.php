<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            HYPELINE ADMIN
        </span>

        <div class="d-flex align-items-center gap-2">
            {{-- Navbar Change Password Button --}}
            <a href="{{ route('admin.password.change') }}" class="btn btn-outline-light btn-sm">
                Change Password
            </a>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>
        </div>

    </div>
</nav>


<div class="container-fluid px-4 py-4">

    {{-- Dashboard Header --}}
    <div class="mb-4">
        <h1 class="fw-bold mb-1">
            Dashboard
        </h1>

        <p class="text-muted mb-0">
            Overview of your Hypeline store.
        </p>
    </div>


    {{-- Today's Overview --}}
    <h5 class="fw-bold mb-3">
        Today's Overview
    </h5>

    <div class="row g-4 mb-5">

        {{-- Today's Orders --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Today's Orders
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $todayOrders }}
                    </h2>

                </div>
            </div>
        </div>


        {{-- Today's Sales --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Today's Sales
                    </p>

                    <h2 class="fw-bold mb-0">
                        ৳{{ number_format($todaySales, 2) }}
                    </h2>

                </div>
            </div>
        </div>


        {{-- Pending Orders --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Pending Orders
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $pendingOrders }}
                    </h2>

                </div>
            </div>
        </div>


        {{-- Total Products --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Total Products
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalProducts }}
                    </h2>

                </div>
            </div>
        </div>

    </div>


    {{-- Order Status --}}
    <h5 class="fw-bold mb-3">
        Order Status
    </h5>

    <div class="row g-4 mb-5">

        @php
            $statuses = [
                [
                    'name' => 'Confirmed',
                    'value' => $confirmedOrders
                ],
                [
                    'name' => 'Processing',
                    'value' => $processingOrders
                ],
                [
                    'name' => 'Shipped',
                    'value' => $shippedOrders
                ],
                [
                    'name' => 'Delivered',
                    'value' => $deliveredOrders
                ],
            ];
        @endphp


        @foreach($statuses as $status)

            <div class="col-12 col-md-6 col-lg-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            {{ $status['name'] }}
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $status['value'] }}
                        </h2>

                    </div>

                </div>

            </div>

        @endforeach

    </div>


    {{-- Inventory --}}
    <h5 class="fw-bold mb-3">
        Inventory
    </h5>

    <div class="row g-4 mb-5">

        {{-- Low Stock --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Low Stock Variants
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $lowStockProducts }}
                    </h2>

                </div>

            </div>

        </div>


        {{-- Out of Stock --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Out of Stock Variants
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $outOfStockProducts }}
                    </h2>

                </div>

            </div>

        </div>


        {{-- Total Orders --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Total Orders
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalOrders }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- Lifetime Sales --}}
    <div class="card border-0 shadow-sm mb-5">

        <div class="card-body p-4">

            <p class="text-muted mb-2">
                Lifetime Sales
            </p>

            <h2 class="fw-bold mb-0">
                ৳{{ number_format($totalSales, 2) }}
            </h2>

        </div>

    </div>


    {{-- Quick Actions --}}
    <h5 class="fw-bold mb-3">
        Quick Actions
    </h5>

    <div class="row g-4 mb-5">

        {{-- New Offline Sale (POS) --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.offline-sales.create') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-success">
                            + New Offline Sale
                        </h5>

                        <p class="text-muted mb-0">
                            Create a direct store bill/receipt.
                        </p>

                    </div>

                </div>

            </a>

        </div>

        {{-- Add Product --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.products.create') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            + Add Product
                        </h5>

                        <p class="text-muted mb-0">
                            Add a new product to your store.
                        </p>

                    </div>

                </div>

            </a>

        </div>

        {{-- Categories --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.categories.index') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            Categories
                        </h5>

                        <p class="text-muted mb-0">
                            View and manage categories.
                        </p>

                    </div>

                </div>

            </a>

        </div>

        {{-- Products & Variants --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.products.index') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            Products & Variants
                        </h5>

                        <p class="text-muted mb-0">
                            Manage inventory variants.
                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- Management Links --}}
    <h5 class="fw-bold mb-3">
        Management
    </h5>

    <div class="row g-4">

        {{-- Online Orders --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.orders.index') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            Online Orders
                        </h5>

                        <p class="text-muted mb-0">
                            Manage customer store orders.
                        </p>

                    </div>

                </div>

            </a>

        </div>

        {{-- Offline Sales --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.offline-sales.index') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            Offline Sales
                        </h5>

                        <p class="text-muted mb-0">
                            View physical store receipts & sales.
                        </p>

                    </div>

                </div>

            </a>

        </div>

        {{-- Inventory --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.inventory.index') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            Inventory
                        </h5>

                        <p class="text-muted mb-0">
                            Monitor stock and product variants.
                        </p>

                    </div>

                </div>

            </a>

        </div>

        {{-- Sales Report --}}
        <div class="col-12 col-md-6 col-lg-3">

            <a href="{{ route('admin.sales-report.index') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h5 class="fw-bold text-dark">
                            Sales Report
                        </h5>

                        <p class="text-muted mb-0">
                            View sales performance and analytics.
                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>


{{-- Footer --}}
<footer class="bg-dark text-white text-center py-3 mt-5">

    <small>
        © {{ date('Y') }} HYPELINE. All rights reserved.
    </small>

</footer>

</body>
</html>