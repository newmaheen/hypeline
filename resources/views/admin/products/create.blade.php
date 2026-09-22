<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">HYPELINE ADMIN</a>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light btn-sm">All Products</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="mb-4">
                    <h2 class="fw-bold mb-1">+ Add New Product</h2>
                    <p class="text-muted mb-0">Create a product with variants and stock.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12 col-lg-7">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Basic Information</h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Product Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Classic Oversized Hoodie" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Category</label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Regular Price (৳)</label>
                                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Sale Price (৳)</label>
                                            <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">Product Images</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Upload Images (Min 3)</label>
                                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                                        <small class="text-muted">Select at least 3 images.</small>
                                    </div>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                        <label class="form-check-label fw-semibold" for="is_active">Publish to Store</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Variants & Initial Stock</h5>
                                        <button type="button" class="btn btn-outline-dark btn-sm" id="addVariantBtn">+ Add Variant</button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Size (M, L, XL)</th>
                                                    <th>Color (Black, White)</th>
                                                    <th>SKU (Optional)</th>
                                                    <th>Stock</th>
                                                    <th style="width: 40px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="variantRows">
                                                <tr class="variant-row">
                                                    <td><input type="text" name="variants[0][size]" class="form-control" placeholder="M" required></td>
                                                    <td><input type="text" name="variants[0][color]" class="form-control" placeholder="Black" required></td>
                                                    <td><input type="text" name="variants[0][sku]" class="form-control" placeholder="Optional"></td>
                                                    <td><input type="number" name="variants[0][stock]" class="form-control" value="10" min="0" required></td>
                                                    <td><button type="button" class="btn btn-outline-danger btn-sm remove-btn" disabled>&times;</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mb-5">
                        <button type="submit" class="btn btn-dark px-5 py-2 fw-semibold">Save Product</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        let vIndex = 1;
        document.getElementById('addVariantBtn').addEventListener('click', function() {
            const firstRow = document.querySelector('.variant-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('input[name*="[size]"]').name = 'variants[' + vIndex + '][size]';
            newRow.querySelector('input[name*="[size]"]').value = '';
            newRow.querySelector('input[name*="[color]"]').name = 'variants[' + vIndex + '][color]';
            newRow.querySelector('input[name*="[color]"]').value = '';
            newRow.querySelector('input[name*="[sku]"]').name = 'variants[' + vIndex + '][sku]';
            newRow.querySelector('input[name*="[sku]"]').value = '';
            newRow.querySelector('input[name*="[stock]"]').name = 'variants[' + vIndex + '][stock]';
            newRow.querySelector('input[name*="[stock]"]').value = '10';

            const removeBtn = newRow.querySelector('.remove-btn');
            removeBtn.disabled = false;
            removeBtn.addEventListener('click', function() {
                newRow.remove();
            });

            document.getElementById('variantRows').appendChild(newRow);
            vIndex++;
        });
    </script>
</body>
</html>