<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $order = DB::transaction(function () use ($request, $cart) {

            $customer = Customer::updateOrCreate(
                ['email' => $request->email],
                [
                    'name'    => $request->name,
                    'phone'   => $request->phone,
                    'address' => $request->address,
                ]
            );

            $total = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status'       => 'pending',
            ]);

            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('order.success', $order);
    }
}