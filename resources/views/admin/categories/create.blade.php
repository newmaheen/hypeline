<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Category - Hypeline</title>

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
                href="{{ route('admin.categories.index') }}"
                class="btn btn-outline-light btn-sm"
            >
                Categories
            </a>

        </div>

    </nav>


    {{-- Page Content --}}

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-md-8 col-lg-6">

                {{-- Heading --}}

                <div class="mb-4">

                    <h1 class="fw-bold mb-1">
                        Create Category
                    </h1>

                    <p class="text-muted mb-0">
                        Add a new product category to your store.
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


                {{-- Form Card --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <form
                            method="POST"
                            action="{{ route('admin.categories.store') }}"
                        >

                            @csrf


                            {{-- Name --}}

                            <div class="mb-4">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >
                                    Category Name
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Jerseys"
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
                                    id="slug"
                                    name="slug"
                                    class="form-control"
                                    value="{{ old('slug') }}"
                                    placeholder="e.g. jerseys"
                                    required
                                >

                                <div class="form-text">
                                    Use lowercase letters, numbers and hyphens.
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
                                    id="description"
                                    name="description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Write a short description..."
                                >{{ old('description') }}</textarea>

                            </div>


                            {{-- Active --}}

                            <div class="form-check mb-4">

                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    class="form-check-input"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >

                                <label
                                    for="is_active"
                                    class="form-check-label fw-semibold"
                                >
                                    Active Category
                                </label>

                            </div>


                            {{-- Buttons --}}

                            <div class="d-flex flex-column flex-sm-row gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-dark px-4"
                                >
                                    Create Category
                                </button>

                                <a
                                    href="{{ route('admin.categories.index') }}"
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
