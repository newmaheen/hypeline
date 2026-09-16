<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
                $variants = ProductVariant::with('product')
            ->latest()
            ->get();

        return view(
            'admin.inventory.index',
            compact('variants')
        );
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $variant->update([
            'stock' => $validated['stock'],
        ]);

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Stock updated successfully.');
    }
}