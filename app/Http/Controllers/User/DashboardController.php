<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Find customer record matching logged-in user's email
        $customer = Customer::where(
            'email',
            Auth::user()->email
        )->first();

        // Default dashboard values
        $totalOrders = 0;
        $pendingOrders = 0;
        $completedOrders = 0;
        $totalProducts = 0;
        $totalSpent = 0;
        $totalSavings = 0;
        $recentOrders = collect();

        if ($customer) {

            // Load orders together with items and products
            $orders = $customer->orders()
                ->with('items.product')
                ->latest()
                ->get();

            // Total Orders
            $totalOrders = $orders->count();

            // Pending Orders
            $pendingOrders = $orders
                ->where('status', 'pending')
                ->count();

            // Completed Orders
            $completedOrdersCollection = $orders
                ->where('status', 'completed');

            $completedOrders = $completedOrdersCollection->count();

            // Total Spent - completed orders only
            $totalSpent = $completedOrdersCollection
                ->sum('total_amount');

            // Total Products Purchased
            $totalProducts = $completedOrdersCollection
                ->flatMap(function ($order) {
                    return $order->items;
                })
                ->sum('quantity');

            // Total Savings
            foreach ($completedOrdersCollection as $order) {

                foreach ($order->items as $item) {

                    if (!$item->product) {
                        continue;
                    }

                    /*
                     * Product regular price
                     * minus actual price paid in order
                     */
                    $regularPrice = (float) $item->product->price;
                    $paidPrice = (float) $item->price;
                    $quantity = (int) $item->quantity;

                    if ($regularPrice > $paidPrice) {

                        $totalSavings +=
                            ($regularPrice - $paidPrice) * $quantity;
                    }
                }
            }

            // Latest 5 orders
            $recentOrders = $orders->take(5);
        }

        return view('user.dashboard', compact(
            'customer',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalProducts',
            'totalSpent',
            'totalSavings',
            'recentOrders'
        ));
    }
}