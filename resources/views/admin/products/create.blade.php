<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Product - Hypeline Admin</title>

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

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-8">

                {{-- Heading --}}

                <div class="mb-4">

                    <h1 class="fw-bold mb-1">
                        Create Product
                    </h1>

                    <p class="text-muted mb-0">
                        Add a new product to your Hypeline store.
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


                {{-- Product Form --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <form
                            method="POST"
                            action="{{ route('products.store') }}"
                            enctype="multipart/form-data"
                        >

                            @csrf


                            {{-- Category --}}

                            <div class="mb-4">

                                <label
                                    for="category_id"
                                    class="form-label fw-semibold"
                                >
                                    Category
                                </label>

                                <select
                                    name="category_id"
                                    id="category_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Category
                                    </option>

                                    @foreach($categories as $category)

                                        <option
                                            value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                                        >
                                            {{ $category->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Product Name --}}

                            <div class="mb-4">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Product Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name') }}"
                                    class="form-control"
                                    placeholder="Example: Argentina Home Jersey"
                                    required
                                >

                            </div>


                            {{-- Slug --}}

                            <div class="mb-4">

                                <label
                                    for="slug"
                                    class="form-label fw-semibold"
                                >
                                    Slug
                                </label>

                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    value="{{ old('slug') }}"
                                    class="form-control"
                                    placeholder="Example: argentina-home-jersey"
                                    required
                                >

                                <div class="form-text">
                                    Use a unique, URL-friendly slug.
                                </div>

                            </div>


                            {{-- Price Row --}}

                            <div class="row g-3 mb-4">

                                <div class="col-12 col-md-6">

                                    <label
                                        for="price"
                                        class="form-label fw-semibold"
                                    >
                                        Price
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ৳
                                        </span>

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="price"
                                            id="price"
                                            value="{{ old('price') }}"
                                            class="form-control"
                                            placeholder="0.00"
                                            min="0"
                                            required
                                        >

                                    </div>

                                </div>


                                <div class="col-12 col-md-6">

                                    <label
                                        for="sale_price"
                                        class="form-label fw-semibold"
                                    >
                                        Sale Price
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            ৳
                                        </span>

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="sale_price"
                                            id="sale_price"
                                            value="{{ old('sale_price') }}"
                                            class="form-control"
                                            placeholder="Optional"
                                            min="0"
                                        >

                                    </div>

                                    <div class="form-text">
                                        Leave empty if there is no sale price.
                                    </div>

                                </div>

                            </div>


                            {{-- Description --}}

                            <div class="mb-4">

                                <label
                                    for="description"
                                    class="form-label fw-semibold"
                                >
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    id="description"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Write a short product description..."
                                >{{ old('description') }}</textarea>

                            </div>


                            {{-- Image --}}

                            <div class="mb-4">

                                <label
                                    for="image"
                                    class="form-label fw-semibold"
                                >
                                    Product Image
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    id="image"
                                    class="form-control"
                                    accept="image/*"
                                >

                                <div class="form-text">
                                    JPEG, PNG, JPG or WEBP. Maximum size: 2MB.
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
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >

                                <label
                                    for="is_active"
                                    class="form-check-label fw-semibold"
                                >
                                    Active
                                </label>

                                <div class="form-text">
                                    Active products are visible in the customer store.
                                </div>

                            </div>


                            {{-- Buttons --}}

                            <div class="d-flex flex-column flex-sm-row gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-dark px-4"
                                >
                                    Create Product
                                </button>

                                <a
                                    href="{{ route('products.index') }}"
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