<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $categories = \App\Models\Category::latest()->get();

    return view('admin.categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
    return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    \App\Models\Category::create($validated);

    return redirect()
    ->route('admin.categories.index')
    ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $category = \App\Models\Category::findOrFail($id);

    return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $category = \App\Models\Category::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $category->update($validated);

    return redirect()
        ->route('categories.index')
        ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $category = \App\Models\Category::findOrFail($id);

    $category->delete();

    return redirect()
        ->route('categories.index')
        ->with('success', 'Category deleted successfully.');
    }
}
