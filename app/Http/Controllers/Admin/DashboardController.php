<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();

        $totalProducts = Product::count();

        $totalCustomers = Customer::count();

        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 'Pending')->count();

        $totalRevenue = Order::where('status', 'Completed')
                            ->sum('total_amount');

        $latestOrders = Order::with('customer')
                            ->latest()
                            ->take(5)
                            ->get();

        $latestProducts = Product::latest()
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'latestOrders',
            'latestProducts'
        ));
    }
}