<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Product Variant - Hypeline Admin</title>

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
                href="{{ route('variants.index', $product->id) }}"
                class="btn btn-outline-light btn-sm"
            >
                ← Variants
            </a>

        </div>

    </nav>


    {{-- Main Content --}}

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-md-8 col-lg-7">

                {{-- Heading --}}

                <div class="mb-4">

                    <h1 class="fw-bold mb-2">
                        Edit Product Variant
                    </h1>

                    <p class="text-muted mb-0">
                        Product:
                        <strong class="text-dark">
                            {{ $product->name }}
                        </strong>
                    </p>

                </div>


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


                {{-- Variant Form --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <form
                            method="POST"
                            action="{{ route('variants.update', [$product->id, $variant->id]) }}"
                        >

                            @csrf

                            @method('PUT')


                            {{-- Size --}}

                            <div class="mb-4">

                                <label
                                    for="size"
                                    class="form-label fw-semibold"
                                >
                                    Size
                                </label>

                                <input
                                    type="text"
                                    name="size"
                                    id="size"
                                    value="{{ old('size', $variant->size) }}"
                                    class="form-control"
                                    placeholder="Example: M, L, XL, 40, 41"
                                >

                                <div class="form-text">
                                    Use clothing sizes or shoe sizes as needed.
                                </div>

                            </div>


                            {{-- Color --}}

                            <div class="mb-4">

                                <label
                                    for="color"
                                    class="form-label fw-semibold"
                                >
                                    Color
                                </label>

                                <input
                                    type="text"
                                    name="color"
                                    id="color"
                                    value="{{ old('color', $variant->color) }}"
                                    class="form-control"
                                    placeholder="Example: Black, Red, White"
                                >

                            </div>


                            {{-- Stock --}}

                            <div class="mb-4">

                                <label
                                    for="stock"
                                    class="form-label fw-semibold"
                                >
                                    Stock
                                </label>

                                <input
                                    type="number"
                                    name="stock"
                                    id="stock"
                                    value="{{ old('stock', $variant->stock) }}"
                                    min="0"
                                    class="form-control"
                                    required
                                >

                            </div>


                            {{-- SKU --}}

                            <div class="mb-4">

                                <label
                                    for="sku"
                                    class="form-label fw-semibold"
                                >
                                    SKU
                                </label>

                                <input
                                    type="text"
                                    name="sku"
                                    id="sku"
                                    value="{{ old('sku', $variant->sku) }}"
                                    class="form-control"
                                    placeholder="Example: ARG-BLK-M"
                                    required
                                >

                                <div class="form-text">
                                    SKU must be unique for each variant.
                                </div>

                            </div>


                            {{-- Active --}}

                            <div class="form-check mb-4">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    id="is_active"
                                    class="form-check-input"
                                    {{ old('is_active', $variant->is_active) ? 'checked' : '' }}
                                >

                                <label
                                    for="is_active"
                                    class="form-check-label fw-semibold"
                                >
                                    Active
                                </label>

                            </div>


                            {{-- Buttons --}}

                            <div class="d-flex flex-column flex-sm-row gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-dark px-4"
                                >
                                    Update Variant
                                </button>

                                <a
                                    href="{{ route('variants.index', $product->id) }}"
                                    class="btn btn-outline-secondary px-4"
                                >
                                    Cancel
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

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