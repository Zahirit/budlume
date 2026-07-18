@extends('admin.layouts.app')

@php
    $title = 'Customer Details';
@endphp

@section('content')
<div class="card-box">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Customer Details</h2>

        <a href="{{ route('admin.customers.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

        {{-- Customer Summary --}}
    @php
        $totalOrders = $customer->orders->count();

        $totalSpent = $customer->orders->sum('total_amount');

        $pendingOrders = $customer->orders
            ->filter(fn($order) => strtolower($order->status) === 'pending')
            ->count();

        $completedOrders = $customer->orders
            ->filter(fn($order) => strtolower($order->status) === 'completed')
            ->count();
    @endphp

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Orders</small>
                    <h3 class="mb-0 mt-2">
                        {{ $totalOrders }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Spent</small>
                    <h3 class="mb-0 mt-2">
                        ${{ number_format($totalSpent, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Pending Orders</small>
                    <h3 class="mb-0 mt-2">
                        {{ $pendingOrders }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Completed Orders</small>
                    <h3 class="mb-0 mt-2">
                        {{ $completedOrders }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Customer Information --}}

    <div class="mb-4">
        <h5>Customer Information</h5>

        <p><strong>Name:</strong> {{ $customer->name }}</p>
        <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
    </div>

    {{-- Order History --}}
    <h5 class="mb-3">Order History</h5>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($customer->orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>

                        <td>
                            @php
                                $status = strtolower($order->status);

                                $badgeClass = match($status) {
                                    'pending'    => 'bg-warning text-dark',
                                    'processing' => 'bg-primary',
                                    'completed'  => 'bg-success',
                                    'cancelled'  => 'bg-danger',
                                    default      => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>{{ $order->created_at->format('d M Y') }}</td>

                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="btn btn-primary btn-sm">
                                View Order
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No orders found for this customer.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection