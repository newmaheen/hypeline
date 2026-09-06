<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::where('id', $validated['variant_id'])
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->firstOrFail();

        if ($variant->stock < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'Requested quantity is not available in stock.',
            ]);
        }

        $cart = session()->get('cart', []);

        $cartKey = $variant->id;

        if (isset($cart[$cartKey])) {

            $newQuantity =
                $cart[$cartKey]['quantity'] +
                $validated['quantity'];

            if ($newQuantity > $variant->stock) {

                return back()->withErrors([
                    'quantity' =>
                        'You cannot add more than available stock.',
                ]);

            }

            $cart[$cartKey]['quantity'] = $newQuantity;

        } else {

            $cart[$cartKey] = [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'size' => $variant->size,
                'color' => $variant->color,
                'price' => $product->sale_price ?? $product->price,
                'quantity' => $validated['quantity'],
            ];

        }

        session()->put('cart', $cart);

        return back()->with(
            'success',
            'Product added to cart.'
        );
    }


    public function index()
    {
        $cart = session()->get('cart', []);

        $total = 0;

        foreach ($cart as $item) {

            $total +=
                $item['price'] *
                $item['quantity'];

        }

        return view(
            'cart.index',
            compact('cart', 'total')
        );
    }

    public function remove($variantId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {

            unset($cart[$variantId]);

        }

        session()->put('cart', $cart);

        return back()->with(
            'success',
            'Product removed from cart.'
        );
    }



    public function update(Request $request, $variantId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$variantId])) {
            return back()->withErrors([
                'quantity' => 'Product not found in cart.',
            ]);
        }

        $variant = ProductVariant::findOrFail($variantId);

        if ($variant->stock < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'Requested quantity is not available in stock.',
            ]);
        }

        $cart[$variantId]['quantity'] = $validated['quantity'];

        session()->put('cart', $cart);

        return back()->with(
            'success',
            'Cart updated successfully.'
        );
    }

    public function clear()
            {
                session()->forget('cart');

                return back()->with('success', 'Cart cleared successfully.');
            }

}