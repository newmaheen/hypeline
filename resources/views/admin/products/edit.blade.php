<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    {{-- Admin Header --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">HYPELINE ADMIN</a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">← Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light btn-sm">← Products</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Edit Product</h2>
                    <p class="text-muted mb-0">Update product details, pricing, and images.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <strong>Please resolve the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        {{-- Basic Info --}}
                        <div class="col-12 col-lg-7">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Basic Information</h5>

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Product Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id" class="form-label fw-semibold">Category</label>
                                        <select name="category_id" id="category_id" class="form-select" required>
                                            <option value="">Select Category</option>
                                            @if (isset($categories))
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                   {{-- Existing Images Preview --}}
                                    @if (!empty($product->images) && is_array($product->images))
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            @foreach ($product->images as $img)
                                                @php
                                                    $previewUrl = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                                                @endphp
                                                <div class="border rounded p-1 bg-white">
                                                    <img src="{{ $previewUrl }}" alt="Preview" width="70" height="70" class="rounded" style="object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small mb-3">No images uploaded yet.</p>
                                    @endif
                                        <div class="col-12 col-md-6">
                                            <label for="sale_price" class="form-label fw-semibold">Sale Price (৳) <small class="text-muted">(Optional)</small></label>
                                            <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Description</label>
                                        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Images & Status --}}
                        <div class="col-12 col-lg-5">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Current Images</h5>

                                    {{-- Existing Images Preview --}}
                                    @if (!empty($product->images) && is_array($product->images))
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            @foreach ($product->images as $img)
                                                <div class="border rounded p-1 bg-white">
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Preview" width="70" height="70" class="rounded" style="object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small mb-3">No images uploaded yet.</p>
                                    @endif

                                    <div class="mb-3">
                                        <label for="images" class="form-label fw-semibold">Replace Images</label>
                                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                                        <small class="text-muted d-block mt-1">If you upload new images, all existing images will be replaced. (Minimum 3 images)</small>
                                    </div>

                                    <hr>

                                    <h5 class="fw-bold mb-3">Status</h5>
                                    <div class="mb-3">
                                        <select name="status" id="status" class="form-select">
                                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('admin.variants.index', $product->id) }}" class="btn btn-outline-secondary w-100">
                                            Manage Product Variants (Stock/Size) →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mb-5">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-dark px-5 py-2 fw-semibold">Update Product</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>