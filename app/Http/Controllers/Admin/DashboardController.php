<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_categories' => Category::count(),
            'recent_orders' => Order::with('customer')->latest()->take(10)->get(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
