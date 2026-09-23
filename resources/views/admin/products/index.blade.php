<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    {{-- Admin Header --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                HYPELINE ADMIN
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                Dashboard
            </a>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="container-fluid px-4 py-4">

        {{-- Heading --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">Products</h1>
                <p class="text-muted mb-0">Manage your Hypeline products and variants.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
                + Create Product
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if($errors->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Products Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                @if($products->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="px-4">ID</th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Sale Price</th>
                                    <th>Status</th>
                                    <th class="text-end px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        {{-- ID --}}
                                        <td class="px-4">
                                            <span class="text-muted">#{{ $product->id }}</span>
                                        </td>

                                        {{-- Image (Cloudinary ও Local দুইটাই সাপোর্ট করবে) --}}
                                        <td>
                                            @php
                                                $firstImage = is_array($product->images) ? ($product->images[0] ?? null) : null;
                                                $imageUrl = $firstImage ? (str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . $firstImage)) : null;
                                            @endphp
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}"
                                                     alt="{{ $product->name }}"
                                                     class="rounded border"
                                                     style="width: 65px; height: 65px; object-fit: cover;">
                                            @else
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                                                     style="width: 65px; height: 65px; font-size: 11px;">
                                                    No Image
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Product Name --}}
                                        <td>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <small class="text-muted">/{{ $product->slug }}</small>
                                        </td>

                                        {{-- Category --}}
                                        <td>
                                            {{ $product->category->name ?? 'N/A' }}
                                        </td>

                                        {{-- Price --}}
                                        <td>
                                            <span class="fw-semibold">
                                                ৳{{ number_format($product->price, 2) }}
                                            </span>
                                        </td>

                                        {{-- Sale Price --}}
                                        <td>
                                            @if($product->sale_price)
                                                <span class="fw-semibold text-success">
                                                    ৳{{ number_format($product->sale_price, 2) }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge text-bg-success">Active</span>
                                            @else
                                                <span class="badge text-bg-secondary">Inactive</span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end px-4">
                                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-dark">
                                                    Edit
                                                </a>

                                                <a href="{{ route('admin.variants.index', $product->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    Variants ({{ $product->variants->count() }})
                                                </a>

                                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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

                    @if($products->hasPages())
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $products->links() }}
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5 px-4">
                        <h5 class="fw-bold">No Products Found</h5>
                        <p class="text-muted mb-4">You have not added any products yet.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
                            + Create First Product
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