@extends('admin.layouts.app')

@php
    $title = 'Admin Dashboard';
@endphp

@section('content')

<h2 class="mb-4">📊 Admin Dashboard</h2>

<div class="row">

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <h5>Total Orders</h5>
                <h2>{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <h5>Total Products</h5>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <h5>Total Customers</h5>
                <h2>{{ $totalCustomers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <h5>Total Categories</h5>
                <h2>{{ $totalCategories }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body">
                <h5>Pending Orders</h5>
                <h2>{{ $pendingOrders }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm bg-dark text-white">
            <div class="card-body">
                <h5>Total Revenue</h5>
                <h2>${{ number_format($totalRevenue, 2) }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-7">

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>Latest Orders</strong>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($latestOrders as $order)

                        <tr>

                            <td>{{ $order->order_number }}</td>

                            <td>{{ $order->customer->name ?? 'Guest' }}</td>

                            <td>${{ number_format($order->total_amount,2) }}</td>

                            <td>{{ $order->status }}</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center">
                                No orders found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-header">
                <strong>Latest Products</strong>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Product</th>
                            <th>Price</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($latestProducts as $product)

                        <tr>

                            <td>{{ $product->name }}</td>

                            <td>${{ number_format($product->price,2) }}</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="2" class="text-center">
                                No products found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection