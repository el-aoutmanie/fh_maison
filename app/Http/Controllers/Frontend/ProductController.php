<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'media', 'variants.images'])
            ->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $locale = app()->getLocale();
            $query->where(function($q) use ($search, $locale) {
                $q->where("name->{$locale}", 'like', "%{$search}%")
                  ->orWhere("description->{$locale}", 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price filter
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->whereHas('variants', function($q) use ($request) {
                if ($request->filled('price_min')) {
                    $q->where('price', '>=', $request->price_min);
                }
                if ($request->filled('price_max')) {
                    $q->where('price', '<=', $request->price_max);
                }
            });
        }

        // In stock filter
        if ($request->boolean('in_stock')) {
            $query->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->leftJoin('variants', 'products.id', '=', 'variants.product_id')
                      ->select('products.*', \DB::raw('MIN(variants.price) as min_price'))
                      ->groupBy('products.id')
                      ->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $query->leftJoin('variants', 'products.id', '=', 'variants.product_id')
                      ->select('products.*', \DB::raw('MAX(variants.price) as max_price'))
                      ->groupBy('products.id')
                      ->orderBy('max_price', 'desc');
                break;
            case 'name':
                $locale = app()->getLocale();
                $query->orderBy("name->{$locale}");
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('frontend.products.index', compact('products', 'categories'));
    }


    public function show($slug)
    {
        $product = Product::with(['category', 'variants', 'variants.images', 'media'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'variants', 'media'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get products in this category
        $query = Product::with(['category', 'variants', 'media'])
            ->where('category_id', $category->id)
            ->where('is_active', true);

        // Apply the same sorting as the index method
        $sort = request()->get('sort', 'newest');
        $locale = app()->getLocale();
        switch ($sort) {
            case 'price_asc':
                $query->leftJoin('variants', 'products.id', '=', 'variants.product_id')
                      ->select('products.*', \DB::raw('MIN(variants.price) as min_price'))
                      ->groupBy('products.id')
                      ->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $query->leftJoin('variants', 'products.id', '=', 'variants.product_id')
                      ->select('products.*', \DB::raw('MAX(variants.price) as max_price'))
                      ->groupBy('products.id')
                      ->orderBy('max_price', 'desc');
                break;
            case 'name':
                $query->orderBy("name->{$locale}");
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::withCount('products')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('frontend.products.index', compact('products', 'categories', 'category'));
    }
}

