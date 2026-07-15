<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $customer = auth()->user();

        $orders = Order::where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('frontend.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->customer_id != auth()->id(), 403);

        $order->load('items.product');

        return view('frontend.order-details', compact('order'));
    }
}