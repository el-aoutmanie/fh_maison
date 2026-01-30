<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category', 'variants', 'images');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name->en', 'like', '%' . $request->search . '%')
                  ->orWhere('name->ar', 'like', '%' . $request->search . '%')
                  ->orWhereHas('variants', function($variantQuery) use ($request) {
                      $variantQuery->where('sku', 'like', '%' . $request->search . '%')
                                  ->orWhere('name->en', 'like', '%' . $request->search . '%')
                                  ->orWhere('name->ar', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.index', compact('products', 'categories'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
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
            'features.*' => 'nullable|string',
            'measuring_unit' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'shipping_price' => 'nullable|numeric|min:0',
            'variants' => 'required|array|min:1',
            'variants.*.name.en' => 'required|string|max:255',
            'variants.*.name.ar' => 'nullable|string|max:255',
            'variants.*.sku' => 'required|string|unique:variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.compare_at_price' => 'nullable|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.is_active' => 'boolean',
            'variants.*.images.*' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'features' => $request->features,
            'slug' => Str::slug($request->name['en']),
            'measuring_unit' => $request->measuring_unit ?? 'piece',
            'category_id' => $request->category_id,
            'is_active' => $request->is_active ?? true,
            'is_featured' => $request->is_featured ?? false,
            'shipping_price' => $request->shipping_price ?? 0,
        ];

        $product = Product::create($data);

        // Create variants
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                $variant = $product->variants()->create([
                    'name' => [
                        'en' => $variantData['name']['en'],
                        'ar' => $variantData['name']['ar'] ?? null,
                    ],
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'compare_at_price' => $variantData['compare_at_price'] ?? null,
                    'quantity' => $variantData['quantity'],
                    'is_active' => $variantData['is_active'] ?? true,
                ]);

                // Handle images for this variant
                if (isset($variantData['images']) && is_array($variantData['images'])) {
                    foreach ($variantData['images'] as $imageIndex => $image) {
                        if ($image && is_file($image)) {
                            $path = $image->store('product', 'public');
                            $fullUrl = asset('storage/' . $path);
                            $product->images()->create([
                                'variant_id' => $variant->id,
                                'name' => $image->getClientOriginalName(),
                                'url' => $fullUrl,
                                'order' => $imageIndex,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', __('Product created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'variants');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('variants.images');
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'features.*' => 'nullable|string',
            'measuring_unit' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'shipping_price' => 'nullable|numeric|min:0',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:variants,id',
            'variants.*.name.en' => 'required|string|max:255',
            'variants.*.name.ar' => 'nullable|string|max:255',
            'variants.*.sku' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.compare_at_price' => 'nullable|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.is_active' => 'boolean',
            'variants.*.images.*' => 'nullable|image|max:2048',
        ]);

        // Manual SKU uniqueness validation for variants
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                $sku = $variantData['sku'];
                $variantId = $variantData['id'] ?? null;

                $query = \App\Models\Variant::where('sku', $sku);
                if ($variantId) {
                    $query->where('id', '!=', $variantId);
                }

                if ($query->exists()) {
                    return back()->withErrors(['variants.' . $index . '.sku' => __('SKU must be unique across all variants')])->withInput();
                }
            }
        }

        // Update product basic info
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'features' => $request->features,
            'slug' => Str::slug($request->name['en']),
            'measuring_unit' => $request->measuring_unit ?? 'piece',
            'category_id' => $request->category_id,
            'is_active' => $request->is_active ?? true,
            'is_featured' => $request->is_featured ?? false,
            'shipping_price' => $request->shipping_price ?? 0,
        ]);

        // Get existing variant IDs
        $existingVariantIds = $product->variants()->pluck('id')->toArray();
        $updatedVariantIds = [];

        // Process variants
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                if (isset($variantData['id']) && !empty($variantData['id'])) {
                    // Update existing variant
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'name' => [
                                'en' => $variantData['name']['en'],
                                'ar' => $variantData['name']['ar'] ?? null,
                            ],
                            'sku' => $variantData['sku'],
                            'price' => $variantData['price'],
                            'compare_at_price' => $variantData['compare_at_price'] ?? null,
                            'quantity' => $variantData['quantity'],
                            'is_active' => $variantData['is_active'] ?? true,
                        ]);
                        $updatedVariantIds[] = $variant->id;

                        // Handle new images for this variant
                        if (isset($variantData['images']) && is_array($variantData['images'])) {
                            $lastOrder = $product->images()->where('variant_id', $variant->id)->max('order') ?? -1;
                            foreach ($variantData['images'] as $imageIndex => $image) {
                                if ($image && is_file($image)) {
                                    $path = $image->store('product', 'public');
                                    $fullUrl = asset('storage/' . $path);
                                    $product->images()->create([
                                        'variant_id' => $variant->id,
                                        'name' => $image->getClientOriginalName(),
                                        'url' => $fullUrl,
                                        'order' => $lastOrder + $imageIndex + 1,
                                    ]);
                                }
                            }
                        }
                    }
                } else {
                    // Create new variant
                    $variant = $product->variants()->create([
                        'name' => [
                            'en' => $variantData['name']['en'],
                            'ar' => $variantData['name']['ar'] ?? null,
                        ],
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'compare_at_price' => $variantData['compare_at_price'] ?? null,
                        'quantity' => $variantData['quantity'],
                        'is_active' => $variantData['is_active'] ?? true,
                    ]);
                    $updatedVariantIds[] = $variant->id;

                    // Handle images for new variant
                    if (isset($variantData['images']) && is_array($variantData['images'])) {
                        foreach ($variantData['images'] as $imageIndex => $image) {
                            if ($image && is_file($image)) {
                                $path = $image->store('product', 'public');
                                $fullUrl = asset('storage/' . $path);
                                $product->images()->create([
                                    'variant_id' => $variant->id,
                                    'name' => $image->getClientOriginalName(),
                                    'url' => $fullUrl,
                                    'order' => $imageIndex,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // Delete variants that were removed
        $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
        if (!empty($variantsToDelete)) {
            $product->variants()->whereIn('id', $variantsToDelete)->delete();
        }

        return redirect()->route('admin.products.index')->with('success', __('Product updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Load variants with images
        $product->load('variants.images');

        // Delete all product images
        foreach ($product->images as $image) {
            // Extract path from URL (remove the base URL and /storage/ prefix)
            $path = str_replace(url('storage/'), '', $image->url);
            Storage::disk('public')->delete($path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', __('Product deleted successfully'));
    }
}
