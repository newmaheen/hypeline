<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Orders - Hypeline Admin</title>

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


    {{-- Page Content --}}

    <div class="container-fluid px-4 py-4">

        {{-- Heading --}}

        <div class="mb-4">

            <h1 class="fw-bold mb-1">
                Orders
            </h1>

            <p class="text-muted mb-0">
                View and manage customer orders.
            </p>

        </div>


        {{-- Success Message --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>

        @endif


        {{-- Orders Table --}}

        <div class="card border-0 shadow-sm">

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
                                        Customer
                                    </th>

                                    <th>
                                        Phone
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th>
                                        Payment Status
                                    </th>

                                    <th>
                                        Transaction ID
                                    </th>

                                    <th>
                                        Order Status
                                    </th>

                                    <th>
                                        Date
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


                                        {{-- Total --}}

                                        <td>

                                            <span class="fw-bold">
                                                ৳{{ number_format($order->total_amount, 2) }}
                                            </span>

                                        </td>


                                        {{-- Payment Method --}}

                                        <td>

                                            <span class="badge text-bg-dark">
                                                {{ strtoupper($order->payment_method) }}
                                            </span>

                                        </td>


                                        {{-- Payment Status --}}

                                        <td>

                                            @if($order->payment_status === 'paid')

                                                <span class="badge text-bg-success">
                                                    Paid
                                                </span>

                                            @elseif($order->payment_status === 'pending')

                                                <span class="badge text-bg-warning">
                                                    Pending
                                                </span>

                                            @else

                                                <span class="badge text-bg-secondary">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Transaction ID --}}

                                        <td>

                                            @if($order->transaction_id)

                                                <span class="small fw-semibold">
                                                    {{ $order->transaction_id }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    N/A
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Order Status --}}

                                        <td>

                                            @switch($order->status)

                                                @case('pending')

                                                    <span class="badge text-bg-warning">
                                                        Pending
                                                    </span>

                                                    @break

                                                @case('confirmed')

                                                    <span class="badge text-bg-primary">
                                                        Confirmed
                                                    </span>

                                                    @break

                                                @case('processing')

                                                    <span class="badge text-bg-info">
                                                        Processing
                                                    </span>

                                                    @break

                                                @case('shipped')

                                                    <span class="badge text-bg-dark">
                                                        Shipped
                                                    </span>

                                                    @break

                                                @case('delivered')

                                                    <span class="badge text-bg-success">
                                                        Delivered
                                                    </span>

                                                    @break

                                                @case('cancelled')

                                                    <span class="badge text-bg-danger">
                                                        Cancelled
                                                    </span>

                                                    @break

                                                @default

                                                    <span class="badge text-bg-secondary">
                                                        {{ ucfirst($order->status) }}
                                                    </span>

                                            @endswitch

                                        </td>


                                        {{-- Date --}}

                                        <td>

                                            <span class="small">
                                                {{ $order->created_at->format('d M Y') }}
                                            </span>

                                            <br>

                                            <span class="text-muted small">
                                                {{ $order->created_at->format('h:i A') }}
                                            </span>

                                        </td>


                                        {{-- Action --}}

                                        <td class="text-end px-4">

                                            <a
                                                href="{{ route('admin.orders.show', $order->order_code) }}"
                                                class="btn btn-sm btn-dark"
                                            >
                                                View
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

                        <p class="text-muted mb-4">
                            There are currently no customer orders.
                        </p>

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="btn btn-dark"
                        >
                            Back to Dashboard
                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- Back Button --}}

        <div class="mt-4">

            <a
                href="{{ route('admin.dashboard') }}"
                class="btn btn-outline-secondary"
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