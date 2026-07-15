@extends('admin.layouts.app')

@php
    $title = 'Orders';
@endphp

@section('content')
<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Order List</h2>
</div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                        <td>{{ $order->customer->phone ?? 'N/A' }}</td>
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
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
</div>
@endsection