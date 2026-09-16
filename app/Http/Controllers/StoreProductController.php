<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StoreProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category', 'variants')
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}