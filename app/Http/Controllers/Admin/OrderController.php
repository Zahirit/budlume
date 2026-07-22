<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'customer',
            'items.product',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Completed,Cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'status'       => 'required|in:Pending,Processing,Completed,Cancelled',
        ]);

        $customer = Customer::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        Order::create([
            'customer_id'  => $customer->id,
            'order_number' => 'ORD-' . now()->format('YmdHis'),
            'total_amount' => $request->total_amount,
            'status'       => $request->status,
        ]);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Test order created successfully.');
    }


public function invoice(Order $order)
{
    $order->load([
        'customer',
        'items.product',
    ]);

    return view('admin.orders.invoice', compact('order'));
}

/**
 * Send invoice email to Guest or Registered customer.
 */
public function sendInvoice(Order $order)
{
    $order->load([
        'customer',
        'items.product',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Find Customer Email
    |--------------------------------------------------------------------------
    | Guest order      = orders.customer_email
    | Registered order = customers.email
    */
    $email = $order->customer_email
        ?: optional($order->customer)->email;

    if (!$email) {
        return back()->with(
            'error',
            'Customer email address was not found.'
        );
    }

    try {

        Mail::to($email)->send(
            new InvoiceMail($order)
        );

        return back()->with(
            'success',
            'Invoice successfully sent to ' . $email
        );

    } catch (\Throwable $e) {

        report($e);

        return back()->with(
            'error',
            'Invoice could not be sent. Please check your mail configuration.'
        );
    }
}

}