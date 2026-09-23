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
            'variants.*.sku' => 'nullable|string|max:100|distinct|unique:product_variants,sku',
        ], [
            'images.min' => 'You must upload at least 3 images for the product.',
            'variants.min' => 'Please add at least one product variant.',
            'variants.*.sku.unique' => 'This SKU has already been taken by another variant. Please provide a unique SKU.',
            'variants.*.sku.distinct' => 'Duplicate SKUs found within the same submission. Each variant SKU must be unique.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Image upload directly via Cloudinary SDK
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

            // 2. Product create with database is_active mapping
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

            // 3. Save variants with guaranteed unique fallback SKU
            foreach ($request->variants as $variantData) {
                $sku = !empty($variantData['sku']) 
                    ? trim($variantData['sku']) 
                    : (strtoupper(Str::slug($product->name)) . '-' . strtoupper($variantData['size']) . '-' . strtoupper(Str::random(4)));

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variantData['size'],
                    'color' => $variantData['color'],
                    'sku' => $sku,
                    'stock' => $variantData['stock'],
                ]);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Product and variants created successfully!');
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
            'images.min' => 'If uploading replacement images, at least 3 images are required.',
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

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if (method_exists($product, 'orderItems') && $product->orderItems()->exists()) {
            return back()->withErrors(['error' => 'This product has associated sales records and cannot be deleted. Please set its status to Inactive instead.']);
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

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}