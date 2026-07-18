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
        $customer = Customer::where('email', Auth::user()->email)->first();

        $totalOrders = 0;
        $pendingOrders = 0;
        $completedOrders = 0;
        $totalSpent = 0;
        $recentOrders = collect();

        if ($customer) {

            $orders = $customer->orders()
                ->latest()
                ->get();

            $totalOrders = $orders->count();

            $pendingOrders = $orders
                ->where('status', 'pending')
                ->count();

            $completedOrders = $orders
                ->where('status', 'completed')
                ->count();

            $totalSpent = $orders
                ->where('status', 'completed')
                ->sum('total_amount');

            $recentOrders = $orders->take(5);
        }

        return view('user.dashboard', compact(
            'customer',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
    }
}