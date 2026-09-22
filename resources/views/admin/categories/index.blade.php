<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Categories - Hypeline</title>

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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>

                <h1 class="fw-bold mb-1">
                    Categories
                </h1>

                <p class="text-muted mb-0">
                    Manage your product categories.
                </p>

            </div>

            <a
                href="{{ route('admin.categories.create') }}"
                class="btn btn-dark"
            >
                + Add Category
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


        {{-- Categories Table --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                @if($categories->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th class="px-4">
                                        ID
                                    </th>

                                    <th>
                                        Name
                                    </th>

                                    <th>
                                        Slug
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end px-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($categories as $category)

                                    <tr>

                                        <td class="px-4 fw-semibold">
                                            {{ $category->id }}
                                        </td>

                                        <td>
                                            <span class="fw-semibold">
                                                {{ $category->name }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-muted">
                                                {{ $category->slug }}
                                            </span>
                                        </td>

                                        <td>

                                            @if($category->is_active)

                                                <span class="badge text-bg-dark">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge text-bg-secondary">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                        <td class="text-end px-4">

                                            <a
                                                href="{{ route('admin.categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-outline-dark"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('admin.categories.destroy', $category->id) }}"
                                                class="d-inline"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?')"
                                                >
                                                    Delete
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
                            No Categories Found
                        </h5>

                        <p class="text-muted mb-4">
                            Create your first product category to get started.
                        </p>

                        <a
                            href="{{ route('admin.categories.create') }}"
                            class="btn btn-dark"
                        >
                            + Add Category
                        </a>

                    </div>

                @endif

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
