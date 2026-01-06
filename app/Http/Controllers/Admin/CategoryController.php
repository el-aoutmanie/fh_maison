<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
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
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug ?? Str::slug($request->name['en']),
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', __('Category created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug ?? Str::slug($request->name['en']),
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image_url) {
                $oldPath = str_replace(url('storage/'), '', $category->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', __('Category updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image_url) {
            $path = str_replace(url('storage/'), '', $category->image_url);
            Storage::disk('public')->delete($path);
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('Category deleted successfully'));
    }
}
