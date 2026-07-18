@extends('admin.layouts.app')

@php
    $title = 'Admin Dashboard';
@endphp

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">Dashboard</h2>
        <p class="text-muted mb-0">
            Welcome back! Here's what's happening today.
        </p>
    </div>

    <span class="badge bg-success fs-6 px-3 py-2">
        Budlume Admin
    </span>

</div>

<div class="row">

        <div class="col-xl-4 col-md-6 mb-4">

    <div class="card dashboard-card bg-orders">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <small>Total Orders</small>

                    <h2>{{ $totalOrders }}</h2>

                </div>

                <div>

                    <i class="bi bi-bag-check-fill"></i>

                </div>

        </div>

        <div class="card-footer">

            <a href="{{ route('admin.orders.index') }}">
                View Orders →
            </a>

        </div>

    </div>

</div>
</div>

<div class="col-xl-4 col-md-6 mb-4">

    <div class="card dashboard-card bg-products">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <small>Total Products</small>
                    <h2>{{ $totalProducts }}</h2>
                </div>

                <i class="bi bi-box-seam"></i>

            </div>

        </div>

    </div>

</div>

<div class="col-xl-4 col-md-6 mb-4">
    <div class="card dashboard-card bg-customers">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Customers</small>
                    <h2>{{ $totalCustomers }}</h2>
                </div>

                <i class="bi bi-people"></i>

            </div>
        </div>
    </div>
</div>

   <div class="col-xl-4 col-md-6 mb-4">
    <div class="card dashboard-card bg-categories">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Categories</small>
                    <h2>{{ $totalCategories }}</h2>
                </div>

                <i class="bi bi-tags"></i>

            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-md-6 mb-4">
    <div class="card dashboard-card bg-pending">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Pending Orders</small>
                    <h2>{{ $pendingOrders }}</h2>
                </div>

                <i class="bi bi-hourglass-split"></i>

            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-md-6 mb-4">
    <div class="card dashboard-card bg-revenue">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Revenue</small>
                    <h2>${{ number_format($totalRevenue, 2) }}</h2>
                </div>

                <i class="bi bi-currency-dollar"></i>

            </div>
        </div>
    </div>
</div>

</div>


    <!-- Sales Overview -->
<div class="row mb-4">

    <div class="col-lg-12">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow text-success"></i>
                        Sales Overview
                    </h5>

                    <span class="badge bg-success">
                        This Month
                    </span>

                </div>

            </div>

            <div class="card-body">
                <canvas id="salesChart" height="90"></canvas>
            </div>

        </div>

    </div>

</div>

<!-- Quick Actions -->

<div class="row mb-4">

    <div class="col-12">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">
                <strong>⚡ Quick Actions</strong>
            </div>

<div class="card-body">

    <div class="row g-3">

        <div class="col-md-3">
            <a href="{{ route('admin.products.create') }}"
               class="btn btn-success w-100 py-3">
                <i class="bi bi-plus-circle"></i><br>
                Add Product
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('admin.categories.create') }}"
               class="btn btn-warning w-100 py-3">
                <i class="bi bi-tags"></i><br>
                Add Category
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-primary w-100 py-3">
                <i class="bi bi-bag-check"></i><br>
                Orders
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('admin.customers.index') }}"
               class="btn btn-dark w-100 py-3">
                <i class="bi bi-people"></i><br>
                Customers
            </a>
        </div>

    </div>

</div>

        </div>

    </div>

</div>

<div class="row">

    {{-- Latest Orders --}}
    <div class="col-lg-8">

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

                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>

                            <td>{{ $order->customer->name ?? 'Guest' }}</td>

                            <td>${{ number_format($order->total_amount,2) }}</td>

                            <td>

                                @if($order->status=='pending')

                                    <span class="badge bg-warning text-dark">Pending</span>

                                @elseif($order->status=='processing')

                                    <span class="badge bg-primary">Processing</span>

                                @elseif($order->status=='completed')

                                    <span class="badge bg-success">Completed</span>

                                @elseif($order->status=='cancelled')

                                    <span class="badge bg-danger">Cancelled</span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($order->status) }}
                                    </span>

                                @endif

                            </td>

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

    {{-- Latest Products --}}
    <div class="col-lg-4">

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>Latest Products</strong>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                            </tr>

                        </thead>

                        <tbody>

                        @forelse($latestProducts as $product)

                            <tr>

                                <td>

                                    @if($product->featured_image)

                                        <img
                                            src="{{ asset('uploads/products/'.$product->featured_image) }}"
                                            width="45"
                                            height="45"
                                            style="border-radius:8px;object-fit:cover;">

                                    @endif

                                </td>

                                <td>

                                    <strong>{{ $product->name }}</strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $product->category->name ?? '-' }}
                                    </small>

                                </td>

                                <td>

                                    ${{ number_format($product->price,2) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="text-center">
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

</div>

<hr class="my-4">

<div class="card shadow-sm mb-4">

    <div class="card-header">
        <strong>👥 Recent Customers</strong>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th width="70">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($latestCustomers as $customer)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>

                            <strong>
                                {{ $customer->name }}
                            </strong>

                        </td>

                        <td>{{ $customer->email }}</td>

                        <td>

                            <strong>
                                {{ $customer->created_at->format('d M Y') }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $customer->created_at->diffForHumans() }}
                            </small>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center">
                            No customers found.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="text-end mt-3">

            <a href="{{ route('admin.customers.index') }}"
               class="btn btn-primary btn-sm">

                View All Customers →

            </a>

        </div>

    </div>

</div>

<div class="card shadow-sm mb-4">

    <div class="card-header">
        <strong>⚠ Low Stock Products</strong>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-center">Stock</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($lowStockProducts as $product)

                    <tr>

                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>

                        <td>
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td class="text-center">

                            <span class="badge bg-danger">
                                {{ $product->stock }} Left
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="text-center">
                            🎉 No low stock products.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="text-end mt-3">

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-danger btn-sm">

                Manage Products →

            </a>

        </div>

    </div>

</div>

<div class="card shadow-sm mb-4">

    <div class="card-header">
        <strong>📩 Recent Contact Messages</strong>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th width="60">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th width="120">Date</th>
                        <th width="90">Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($recentMessages as $message)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ $message->name }}</strong>
                        </td>

                        <td>{{ $message->email }}</td>

                        <td>{{ $message->subject }}</td>

                        <td>
                            <small>{{ optional($message->created_at)->format('d M Y') ?? '-' }}</small>
                        </td>

                        <td>

                            <a href="{{ route('admin.contact-messages.index') }}"
                               class="btn btn-sm btn-primary">

                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            No contact messages found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="text-end mt-3">

            <a href="{{ route('admin.contact-messages.index') }}"
               class="btn btn-dark btn-sm">

                View All Messages →

            </a>

        </div>

    </div>

</div>
<div class="card shadow-sm mb-4">

    <div class="card-header">

        <strong>📊 Order Status Summary</strong>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-3">

                <div class="card border-warning">

                    <div class="card-body text-center">

                        <h6 class="text-warning">Pending</h6>

                        <h2>{{ $pendingOrders }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-primary">

                    <div class="card-body text-center">

                        <h6 class="text-primary">Processing</h6>

                        <h2>{{ $processingOrders }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-success">

                    <div class="card-body text-center">

                        <h6 class="text-success">Completed</h6>

                        <h2>{{ $completedOrders }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-danger">

                    <div class="card-body text-center">

                        <h6 class="text-danger">Cancelled</h6>

                        <h2>{{ $cancelledOrders }}</h2>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

