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
use Cloudinary\Cloudinary;

class ProductController extends Controller
{
    /**
     * Helper to get configured Cloudinary instance
     */
    private function getCloudinaryClient(): Cloudinary
    {
        $cloudinaryUrl = env('CLOUDINARY_URL') ?: (
            (env('CLOUDINARY_API_KEY') && env('CLOUDINARY_API_SECRET') && env('CLOUDINARY_CLOUD_NAME'))
                ? 'cloudinary://' . env('CLOUDINARY_API_KEY') . ':' . env('CLOUDINARY_API_SECRET') . '@' . env('CLOUDINARY_CLOUD_NAME')
                : null
        );

        return new Cloudinary($cloudinaryUrl);
    }

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
            // ১. ছবি Cloudinary-তে সরাসরি SDK দিয়ে আপলোড
            $imagePaths = [];
            if ($request->hasFile('images')) {
                $cloudinary = $this->getCloudinaryClient();

                foreach ($request->file('images') as $file) {
                    $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                        'folder' => 'hypeline_products'
                    ]);
                    $imagePaths[] = $uploadResult['secure_url'];
                }
            }

            // ২. প্রোডাক্ট তৈরি (ডাটাবেজ কলাম is_active অনুযায়ী)
            $isActive = $request->has('is_active') || $request->input('status') === 'active';

            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . Str::lower(Str::random(5)),
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'images' => $imagePaths,
                'is_active' => $isActive ? 1 : 0,
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
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];

        if ($request->hasFile('images')) {
            $rules['images'] = 'array|min:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,webp|max:4096';
        }

        $request->validate($rules, [
            'images.min' => 'নতুন ছবি দিলে কমপক্ষে ৩টি ছবি আপলোড করতে হবে।',
        ]);

        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->sale_price = $request->sale_price;
        $product->is_active = ($request->status === 'active') ? 1 : 0;

        if ($request->hasFile('images')) {
            $cloudinary = $this->getCloudinaryClient();

            $newPaths = [];
            foreach ($request->file('images') as $file) {
                $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'hypeline_products'
                ]);
                $newPaths[] = $uploadResult['secure_url'];
            }
            $product->images = $newPaths;
        }

        $product->save();

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