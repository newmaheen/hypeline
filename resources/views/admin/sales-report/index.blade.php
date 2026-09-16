<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sales Report - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-light">

    {{-- Admin Header --}}

    <nav class="navbar navbar-dark bg-dark shadow-sm">

        <div class="container-fluid px-4">

            <a
                href="{{ route('admin.dashboard') }}"
                class="navbar-brand fw-bold"
            >
                HYPELINE ADMIN
            </a>

            <a
                href="{{ route('admin.dashboard') }}"
                class="btn btn-outline-light btn-sm"
            >
                Dashboard
            </a>

        </div>

    </nav>


    {{-- Main Content --}}

    <div class="container-fluid px-4 py-4">

        {{-- Heading --}}

        <div class="mb-4">

            <h1 class="fw-bold mb-1">
                Sales Report
            </h1>

            <p class="text-muted mb-0">
                View sales and order performance for a selected period.
            </p>

        </div>


        {{-- Report Period --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <p class="text-muted mb-1">
                    Report Period
                </p>

                <h5 class="fw-bold mb-0">

                    {{ $startDate->format('d M Y') }}

                    <span class="text-muted mx-2">
                        →
                    </span>

                    {{ $endDate->format('d M Y') }}

                </h5>

            </div>

        </div>


        {{-- Filter Report --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">
                    Filter Report
                </h5>

               <div class="d-flex flex-wrap gap-2 mb-4">

                            <a
                                href="{{ route('admin.sales-report.index', ['period' => 'today']) }}"
                                class="btn {{ request('period', 'today') === 'today' ? 'btn-dark' : 'btn-outline-dark' }}"
                            >
                                Today
                            </a>

                            <a
                                href="{{ route('admin.sales-report.index', ['period' => 'yesterday']) }}"
                                class="btn {{ request('period') === 'yesterday' ? 'btn-dark' : 'btn-outline-dark' }}"
                            >
                                Yesterday
                            </a>

                            <a
                                href="{{ route('admin.sales-report.index', ['period' => 'this_week']) }}"
                                class="btn {{ request('period') === 'this_week' ? 'btn-dark' : 'btn-outline-dark' }}"
                            >
                                This Week
                            </a>

                            <a
                                href="{{ route('admin.sales-report.index', ['period' => 'this_month']) }}"
                                class="btn {{ request('period') === 'this_month' ? 'btn-dark' : 'btn-outline-dark' }}"
                            >
                                This Month
                            </a>

                            <a
                                href="{{ route('admin.sales-report.index', ['period' => 'last_month']) }}"
                                class="btn {{ request('period') === 'last_month' ? 'btn-dark' : 'btn-outline-dark' }}"
                            >
                                Last Month
                            </a>

                            <a
                                href="{{ route('admin.sales-report.index', ['period' => 'this_year']) }}"
                                class="btn {{ request('period') === 'this_year' ? 'btn-dark' : 'btn-outline-dark' }}"
                            >
                                This Year
                            </a>

                        </div>


                {{-- Custom Date Range --}}

                <div class="border-top pt-4">

                    <h6 class="fw-bold mb-3">
                        Custom Date Range
                    </h6>

                    <form
                        method="GET"
                        action="{{ route('admin.sales-report.index') }}"
                    >

                        <input
                            type="hidden"
                            name="period"
                            value="custom"
                        >

                        <div class="row g-3 align-items-end">

                            <div class="col-12 col-md-4">

                                <label
                                    for="start_date"
                                    class="form-label fw-semibold"
                                >
                                    Start Date
                                </label>

                                <input
                                    type="date"
                                    name="start_date"
                                    id="start_date"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <div class="col-12 col-md-4">

                                <label
                                    for="end_date"
                                    class="form-label fw-semibold"
                                >
                                    End Date
                                </label>

                                <input
                                    type="date"
                                    name="end_date"
                                    id="end_date"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <div class="col-12 col-md-4">

                                <button
                                    type="submit"
                                    class="btn btn-dark w-100"
                                >
                                    Generate Report
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Summary Cards --}}

        <div class="row g-3 mb-4">

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


            <div class="col-12 col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            Total Sales
                        </p>

                        <h2 class="fw-bold mb-0">
                            ৳{{ number_format($totalSales, 2) }}
                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-12 col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <p class="text-muted mb-2">
                            Cancelled Orders
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $cancelledOrders }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>


        {{-- Orders --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 p-4">

                <h5 class="fw-bold mb-1">
                    Orders
                </h5>

                <p class="text-muted small mb-0">
                    Orders included in this report period.
                </p>

            </div>


            <div class="card-body p-0">

                @if($orders->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th class="px-4">
                                        Order ID
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Phone
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th class="text-end px-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($orders as $order)

                                    <tr>

                                        {{-- Order ID --}}

                                        <td class="px-4">

                                            <span class="fw-bold">
                                                #{{ $order->order_code }}
                                            </span>

                                        </td>


                                        {{-- Date --}}

                                        <td>

                                            <div>
                                                {{ $order->created_at->format('d M Y') }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $order->created_at->format('h:i A') }}
                                            </small>

                                        </td>


                                        {{-- Customer --}}

                                        <td>

                                            <span class="fw-semibold">
                                                {{ $order->customer_name }}
                                            </span>

                                        </td>


                                        {{-- Phone --}}

                                        <td>
                                            {{ $order->customer_phone }}
                                        </td>


                                        {{-- Payment --}}

                                        <td>

                                            <span class="badge text-bg-light border">
                                                {{ strtoupper($order->payment_method) }}
                                            </span>

                                        </td>


                                        {{-- Status --}}

                                        <td>

                                            @if($order->status === 'pending')

                                                <span class="badge text-bg-warning">
                                                    Pending
                                                </span>

                                            @elseif($order->status === 'confirmed')

                                                <span class="badge text-bg-info">
                                                    Confirmed
                                                </span>

                                            @elseif($order->status === 'processing')

                                                <span class="badge text-bg-primary">
                                                    Processing
                                                </span>

                                            @elseif($order->status === 'shipped')

                                                <span class="badge text-bg-secondary">
                                                    Shipped
                                                </span>

                                            @elseif($order->status === 'delivered')

                                                <span class="badge text-bg-success">
                                                    Delivered
                                                </span>

                                            @elseif($order->status === 'cancelled')

                                                <span class="badge text-bg-danger">
                                                    Cancelled
                                                </span>

                                            @else

                                                <span class="badge text-bg-secondary">
                                                    {{ ucfirst($order->status) }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Total --}}

                                        <td>

                                            <span class="fw-bold">
                                                ৳{{ number_format($order->total_amount, 2) }}
                                            </span>

                                        </td>


                                        {{-- Action --}}

                                        <td class="text-end px-4">

                                            <a
                                                href="{{ route('admin.orders.show', $order->order_code) }}"
                                                class="btn btn-sm btn-outline-dark"
                                            >
                                                View Order
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    {{-- Empty State --}}

                    <div class="text-center py-5 px-4">

                        <h5 class="fw-bold">
                            No Orders Found
                        </h5>

                        <p class="text-muted mb-0">
                            No orders were found for this report period.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Back Button --}}

        <div class="mt-4">

            <a
                href="{{ route('admin.dashboard') }}"
                class="btn btn-outline-dark"
            >
                ← Back to Dashboard
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