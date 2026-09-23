<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'images' => 'required|array|min:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
        ], [
            'images.min' => 'প্রোডাক্টের জন্য কমপক্ষে ৩টি ছবি আপলোড করতে হবে।',
            'variants.min' => 'কমপক্ষে একটি সাইজ/কালার ভ্যারিয়েন্ট যোগ করতে হবে।',
        ]);

        DB::transaction(function () use ($request) {
            // ১. ছবি Cloudinary-তে পার্মানেন্ট আপলোড
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $uploadedFile = cloudinary()->upload($file->getRealPath(), [
                        'folder' => 'hypeline_products'
                    ])->getSecurePath();
                    $imagePaths[] = $uploadedFile;
                }
            }

            // ২. প্রোডাক্ট তৈরি
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . Str::lower(Str::random(5)),
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'images' => $imagePaths,
                'status' => $request->has('is_active') ? 'active' : 'inactive',
            ]);

            // ৩. ভ্যারিয়েন্ট ও স্টক সেভ
            foreach ($request->variants as $variantData) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variantData['size'],
                    'color' => $variantData['color'],
                    'sku' => $variantData['sku'] ?? (strtoupper(Str::slug($product->name)) . '-' . strtoupper($variantData['size']) . '-' . rand(100, 999)),
                    'stock' => $variantData['stock'],
                ]);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'প্রোডাক্ট এবং ভ্যারিয়েন্ট সফলভাবে তৈরি হয়েছে!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|min:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'status' => $request->status ?? $product->status,
        ];

        if ($request->hasFile('images')) {
            // নতুন ছবি আপলোড Cloudinary-তে
            $newPaths = [];
            foreach ($request->file('images') as $file) {
                $uploadedFile = cloudinary()->upload($file->getRealPath(), [
                    'folder' => 'hypeline_products'
                ])->getSecurePath();
                $newPaths[] = $uploadedFile;
            }
            $data['images'] = $newPaths;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'প্রোডাক্ট সফলভাবে আপডেট হয়েছে!');
    }

    public function destroy(Product $product)
    {
        if (method_exists($product, 'orderItems') && $product->orderItems()->exists()) {
            return back()->withErrors(['error' => 'এই প্রোডাক্টের অতীত সেলস রেকর্ড আছে, তাই ডিলিট করা যাবে না। স্ট্যাটাস Inactive করুন।']);
        }

        if (!empty($product->images) && is_array($product->images)) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'প্রোডাক্ট মুছে ফেলা হয়েছে!');
    }
}