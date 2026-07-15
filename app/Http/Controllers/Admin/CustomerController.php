<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }
    public function show(Customer $customer)
    {
        $customer->load([
            'orders' => function ($query) {
                $query->latest();
            }
        ]);

        return view('admin.customers.show', compact('customer'));
    }
}