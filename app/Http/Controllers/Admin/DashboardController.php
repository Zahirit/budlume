<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();

        $totalProducts = Product::count();

        $totalCustomers = Customer::count();

        $totalOrders = Order::count();

        $pendingOrders = Order::where('status', 'Pending')->count();

        $processingOrders = Order::where('status', 'Processing')->count();

        $completedOrders = Order::where('status', 'Completed')->count();

        $cancelledOrders = Order::where('status', 'Cancelled')->count();

        $totalRevenue = Order::where('status', 'Completed')
                            ->sum('total_amount');

        $latestOrders = Order::with('customer')
                            ->latest()
                            ->take(5)
                            ->get();

        $latestProducts = Product::with('category')
                            ->latest()
                            ->take(5)
                            ->get();

        $latestCustomers = Customer::latest()
                            ->take(5)
                            ->get();

        $lowStockProducts = Product::with('category')
                            ->where('stock', '<=', 5)
                            ->orderBy('stock')
                            ->take(5)
                            ->get();

        $recentMessages = ContactMessage::latest()
                            ->take(5)
                            ->get();                    

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'latestOrders',
            'latestProducts',
            'latestCustomers',
            'lowStockProducts',
            'recentMessages'
        ));
    }
}