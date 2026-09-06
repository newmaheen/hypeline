<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Product Variants - Hypeline Admin</title>

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
                href="{{ route('products.index') }}"
                class="btn btn-outline-light btn-sm"
            >
                ← Products
            </a>

        </div>

    </nav>


    {{-- Main Content --}}

    <div class="container-fluid px-4 py-4">

        {{-- Heading --}}

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>

                <h1 class="fw-bold mb-1">
                    Product Variants
                </h1>

                <p class="text-muted mb-0">
                    Manage size, color, SKU and stock for
                    <strong class="text-dark">
                        {{ $product->name }}
                    </strong>
                </p>

            </div>

            <a
                href="{{ route('variants.create', $product->id) }}"
                class="btn btn-dark"
            >
                + Add Variant
            </a>

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


        {{-- Variants Table --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if($variants->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th class="px-4">
                                        ID
                                    </th>

                                    <th>
                                        Size
                                    </th>

                                    <th>
                                        Color
                                    </th>

                                    <th>
                                        Stock
                                    </th>

                                    <th>
                                        SKU
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end px-4">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($variants as $variant)

                                    <tr>

                                        <td class="px-4">

                                            <span class="text-muted">
                                                #{{ $variant->id }}
                                            </span>

                                        </td>


                                        <td>

                                            @if($variant->size)

                                                <span class="badge text-bg-light border">
                                                    {{ $variant->size }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    N/A
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            {{ $variant->color ?? 'N/A' }}

                                        </td>


                                        <td>

                                            @if($variant->stock == 0)

                                                <span class="fw-bold text-danger">
                                                    0
                                                </span>

                                            @elseif($variant->stock <= 5)

                                                <span class="fw-bold text-warning">
                                                    {{ $variant->stock }}
                                                </span>

                                            @else

                                                <span class="fw-bold">
                                                    {{ $variant->stock }}
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <span class="small text-muted">
                                                {{ $variant->sku }}
                                            </span>

                                        </td>


                                        <td>

                                            @if($variant->is_active)

                                                <span class="badge text-bg-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge text-bg-secondary">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-end px-4">

                                            <div class="d-flex justify-content-end gap-2">

                                                <a
                                                    href="{{ route('variants.edit', [$product->id, $variant->id]) }}"
                                                    class="btn btn-sm btn-outline-dark"
                                                >
                                                    Edit
                                                </a>


                                                <form
                                                    method="POST"
                                                    action="{{ route('variants.destroy', [$product->id, $variant->id]) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this variant?');"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

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
                            No Variants Found
                        </h5>

                        <p class="text-muted mb-4">
                            This product does not have any variants yet.
                        </p>

                        <a
                            href="{{ route('variants.create', $product->id) }}"
                            class="btn btn-dark"
                        >
                            + Add First Variant
                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- Back to Products --}}

        <div class="mt-4">

            <a
                href="{{ route('products.index') }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Products
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