<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category', 'variants')
            ->active()
            ->latest()
            ->take(8)
            ->get();
            
        $categories = Category::active()
            ->withCount('products')
            ->orderBy('name')
            ->get();    
            
        $services = Service::active()
            ->take(4)
            ->get();
            
        return view('frontend.home.index', compact('featuredProducts', 'categories', 'services'));
    }
}
