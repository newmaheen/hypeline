<div style="padding: 50px; font-family: sans-serif;">
    <h1 style="color: #111;">Product: {{ $product->name }}</h1>
    <p>Price: ৳{{ number_format($product->price, 0) }}</p>
    <p style="color: green; font-weight: bold;">If you see this, the correct file is working!</p>
</div>