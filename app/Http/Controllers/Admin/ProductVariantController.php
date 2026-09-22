<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->latest()->get();

        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['product_id'] = $product->id;
        $validated['is_active'] = $request->has('is_active');

        ProductVariant::create($validated);

        return redirect()
            ->route('admin.variants.index', $product->id)
            ->with('success', 'Variant created successfully.');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $validated = $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $variant->update($validated);

        return redirect()
            ->route('admin.variants.index', $product->id)
            ->with('success', 'Variant updated successfully.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $variant->delete();

        return redirect()
            ->route('admin.variants.index', $product->id)
            ->with('success', 'Variant deleted successfully.');
    }
}