<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Variants - {{ $product->name }} - Hypeline Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    {{-- Admin Header --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                HYPELINE ADMIN
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light btn-sm">
                    ← Back to Products
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Product Variants</h2>
                        <p class="text-muted mb-0">Managing sizes, colors and stocks for: <strong>{{ $product->name }}</strong></p>
                    </div>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-dark btn-sm">
                        Edit Product
                    </a>
                </div>

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row g-4">
                    {{-- Variant List Table --}}
                    <div class="col-12 col-lg-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0">Existing Variants</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="ps-3">Size</th>
                                                <th>Color</th>
                                                <th>SKU</th>
                                                <th>Stock</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($product->variants as $variant)
                                                <tr>
                                                    <td class="ps-3 fw-bold">{{ $variant->size }}</td>
                                                    <td>{{ $variant->color }}</td>
                                                    <td><small class="text-muted">{{ $variant->sku ?? 'N/A' }}</small></td>
                                                    <td>
                                                        <span class="badge {{ $variant->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $variant->stock }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <form method="POST" action="{{ route('admin.variants.destroy', [$product->id, $variant->id]) }}" onsubmit="return confirm('Are you sure you want to delete this variant?');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        No variants added yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Add New Variant Form --}}
                    <div class="col-12 col-lg-5">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">+ Add New Variant</h5>

                                <form method="POST" action="{{ route('admin.variants.store', $product->id) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Size</label>
                                        <input type="text" name="size" class="form-control" placeholder="e.g. M, L, XL" value="{{ old('size') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Color</label>
                                        <input type="text" name="color" class="form-control" placeholder="e.g. Black, White" value="{{ old('color') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">SKU <small class="text-muted">(Optional)</small></label>
                                        <input type="text" name="sku" class="form-control" placeholder="e.g. TSHIRT-BLK-M" value="{{ old('sku') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Stock Quantity</label>
                                        <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', 10) }}" required>
                                    </div>

                                    <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold">
                                        Add Variant
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>