<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Hypeline</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .product-thumbnail {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        .product-thumbnail.active {
            border-color: #000;
        }
        .zoom-container {
            overflow: hidden;
            position: relative;
            cursor: crosshair;
        }
        .zoom-image {
            transition: transform 0.2s ease-out;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">HYPELINE</a>
            <a href="/" class="btn btn-outline-light btn-sm">&larr; Back to Shop</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-5">

            {{-- Image Gallery with Zoom --}}
            <div class="col-12 col-md-6">
                @php
                    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                    $firstImage = (!empty($images) && count($images) > 0) ? $images[0] : null;
                @endphp

                <div class="card border-0 shadow-sm overflow-hidden mb-3">
                    <div class="zoom-container" id="mainZoomContainer" style="height: 480px;">
                        @if ($firstImage)
                            <img id="mainImage" src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}" class="zoom-image">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 bg-secondary text-white">
                                No Image Available
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Thumbnails --}}
                @if (!empty($images) && count($images) > 1)
                    <div class="d-flex gap-2">
                        @foreach ($images as $key => $img)
                            <img src="{{ asset('storage/' . $img) }}" 
                                 alt="Thumbnail" 
                                 class="product-thumbnail rounded {{ $key === 0 ? 'active' : '' }}" 
                                 width="75" 
                                 height="75" 
                                 style="object-fit: cover;" 
                                 onclick="changeImage(this, '{{ asset('storage/' . $img) }}')">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Product Information & Add to Cart --}}
            <div class="col-12 col-md-6">
                <span class="badge bg-secondary mb-2">{{ $product->category->name ?? 'Collection' }}</span>
                <h1 class="fw-bold mb-2">{{ $product->name }}</h1>

                <div class="mb-4">
                    @if ($product->sale_price)
                        <span class="fs-3 fw-bold text-danger">&#2547;{{ number_format($product->sale_price, 2) }}</span>
                        <span class="fs-5 text-muted text-decoration-line-through ms-2">&#2547;{{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="fs-3 fw-bold text-dark">&#2547;{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <div class="mb-4 text-muted" style="line-height: 1.6;">
                    {!! nl2br(e($product->description)) !!}
                </div>

                {{-- Order / Cart Form --}}
                <form action="#" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Variants --}}
                    @if ($product->variants && $product->variants->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select Size & Color</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($product->variants as $variant)
                                    @if ($variant->stock > 0)
                                        <div class="form-check p-0">
                                            <input type="radio" class="btn-check" name="variant_id" id="v_{{ $variant->id }}" value="{{ $variant->id }}" required>
                                            <label class="btn btn-outline-dark" for="v_{{ $variant->id }}">
                                                {{ $variant->size }} ({{ $variant->color }})
                                                <small class="text-muted d-block">Stock: {{ $variant->stock }}</small>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row g-3 mb-4 align-items-center">
                        <div class="col-4 col-sm-3">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="10">
                        </div>
                        <div class="col-8 col-sm-9 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Image Switcher & Hover Zoom Script --}}
    <script>
        function changeImage(element, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.product-thumbnail').forEach(function(el) {
                el.classList.remove('active');
            });
            element.classList.add('active');
        }

        const container = document.getElementById('mainZoomContainer');
        const img = document.getElementById('mainImage');

        if (container && img) {
            container.addEventListener('mousemove', function(e) {
                const rect = container.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;

                img.style.transformOrigin = x + '% ' + y + '%';
                img.style.transform = 'scale(1.8)';
            });

            container.addEventListener('mouseleave', function() {
                img.style.transformOrigin = 'center center';
                img.style.transform = 'scale(1)';
            });
        }
    </script>
</body>
</html>