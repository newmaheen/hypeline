<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-light">

    {{-- Admin Header --}}

    <nav class="navbar navbar-dark bg-dark shadow-sm">

        <div class="container-fluid px-4">

            <span class="navbar-brand fw-bold">
                HYPELINE ADMIN
            </span>

            <form
                method="POST"
                action="{{ route('admin.logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-light btn-sm"
                >
                    Logout
                </button>

            </form>

        </div>

    </nav>


    {{-- Dashboard Content --}}

    <div class="container-fluid px-4 py-4">

        {{-- Heading --}}

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

            <div class="col-12 col-md-6 col-xl-3">

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

            <div class="col-12 col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            Today's Sales
                        </p>

                        <h2 class="fw-bold mb-0">
                            ৳ {{ number_format($todaySales, 2) }}
                        </h2>

                    </div>

                </div>

            </div>


            {{-- Pending Orders --}}

            <div class="col-12 col-md-6 col-xl-3">

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

            <div class="col-12 col-md-6 col-xl-3">

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
                    ['name' => 'Confirmed', 'value' => $confirmedOrders],
                    ['name' => 'Processing', 'value' => $processingOrders],
                    ['name' => 'Shipped', 'value' => $shippedOrders],
                    ['name' => 'Delivered', 'value' => $deliveredOrders],
                ];

            @endphp


            @foreach($statuses as $status)

                <div class="col-12 col-sm-6 col-lg-3">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <p class="text-muted mb-2">
                                {{ $status['name'] }} Orders
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $status['value'] }}
                            </h3>

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

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            Low Stock Variants
                        </p>

                        <h3 class="fw-bold mb-0">
                            {{ $lowStockProducts }}
                        </h3>

                    </div>

                </div>

            </div>


            {{-- Out of Stock --}}

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            Out of Stock Variants
                        </p>

                        <h3 class="fw-bold mb-0">
                            {{ $outOfStockProducts }}
                        </h3>

                    </div>

                </div>

            </div>


            {{-- Total Orders --}}

            <div class="col-12 col-md-12 col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            Total Orders
                        </p>

                        <h3 class="fw-bold mb-0">
                            {{ $totalOrders }}
                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Overall Sales --}}

        <div class="card border-0 shadow-sm mb-5">

            <div class="card-body p-4 p-md-5">

                <p class="text-muted mb-2">
                    Lifetime Sales
                </p>

                <h2 class="fw-bold mb-0">
                    ৳ {{ number_format($totalSales, 2) }}
                </h2>

            </div>

        </div>


        {{-- Quick Actions --}}

<h5 class="fw-bold mb-3">
    Quick Actions
</h5>

<div class="row g-4 mb-5">

    {{-- Add Product --}}

    <div class="col-12 col-md-6">

        <a
            href="{{ route('products.create') }}"
            class="text-decoration-none"
        >

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


    {{-- Products & Variants --}}

    <div class="col-12 col-md-6">

        <a
            href="{{ route('products.index') }}"
            class="text-decoration-none"
        >

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold text-dark">
                        Products & Variants
                    </h5>

                    <p class="text-muted mb-0">
                        View products and manage their variants.
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

            {{-- Orders --}}

            <div class="col-12 col-md-4">

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="text-decoration-none"
                >

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <h5 class="fw-bold text-dark">
                                Orders
                            </h5>

                            <p class="text-muted mb-0">
                                View and manage customer orders.
                            </p>

                        </div>

                    </div>

                </a>

            </div>


            {{-- Inventory --}}

            <div class="col-12 col-md-4">

                <a
                    href="{{ route('admin.inventory.index') }}"
                    class="text-decoration-none"
                >

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <h5 class="fw-bold text-dark">
                                Inventory
                            </h5>

                            <p class="text-muted mb-0">
                                Manage product variant stock.
                            </p>

                        </div>

                    </div>

                </a>

            </div>


            {{-- Sales Report --}}

            <div class="col-12 col-md-4">

                <a
                    href="{{ route('admin.sales-report.index') }}"
                    class="text-decoration-none"
                >

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <h5 class="fw-bold text-dark">
                                Sales Report
                            </h5>

                            <p class="text-muted mb-0">
                                View sales and order reports.
                            </p>

                        </div>

                    </div>

                </a>

            </div>

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