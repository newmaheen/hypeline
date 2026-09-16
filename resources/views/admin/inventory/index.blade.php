<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Inventory - Hypeline</title>

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
                Inventory Management
            </h1>

            <p class="text-muted mb-0">
                Monitor and update product variant stock.
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


        {{-- Validation Errors --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Inventory Table --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if($variants->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th class="px-4">
                                        Product
                                    </th>

                                    <th>
                                        Size
                                    </th>

                                    <th>
                                        Color
                                    </th>

                                    <th>
                                        SKU
                                    </th>

                                    <th>
                                        Stock
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end px-4">
                                        Update
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($variants as $variant)

                                    <tr>

                                        <td class="px-4">

                                            <span class="fw-semibold">
                                                {{ $variant->product->name }}
                                            </span>

                                        </td>


                                        <td>

                                            {{ $variant->size ?? 'N/A' }}

                                        </td>


                                        <td>

                                            {{ $variant->color ?? 'N/A' }}

                                        </td>


                                        <td>

                                            <span class="text-muted small">
                                                {{ $variant->sku }}
                                            </span>

                                        </td>


                                        <td>

                                            <span class="fw-bold">
                                                {{ $variant->stock }}
                                            </span>

                                        </td>


                                        <td>

                                            @if($variant->stock == 0)

                                                <span class="badge text-bg-danger">
                                                    Out of Stock
                                                </span>

                                            @elseif($variant->stock <= 5)

                                                <span class="badge text-bg-warning">
                                                    Low Stock
                                                </span>

                                            @else

                                                <span class="badge text-bg-dark">
                                                    In Stock
                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-4">

                                            <form
                                                method="POST"
                                                action="{{ route('admin.inventory.update', $variant->id) }}"
                                                class="d-flex justify-content-end gap-2"
                                            >

                                                @csrf

                                                @method('PUT')

                                                <input
                                                    type="number"
                                                    name="stock"
                                                    value="{{ $variant->stock }}"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                    style="width: 90px;"
                                                    required
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-dark"
                                                >
                                                    Update
                                                </button>

                                            </form>

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
                            No Inventory Found
                        </h5>

                        <p class="text-muted mb-4">
                            There are currently no product variants in your inventory.
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