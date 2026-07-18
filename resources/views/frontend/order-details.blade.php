@extends('frontend.layouts.app')

@section('title', 'Order Details')

@section('content')

<div class="container py-5">

    <h2 class="mb-4">Order Details</h2>

    <div class="card mb-4">
        <div class="card-body">

            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>

            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>

            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

            <p><strong>Total:</strong>
                ${{ number_format($order->total_amount,2) }}
            </p>

        </div>
    </div>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Product</th>
                <th width="120">Price</th>
                <th width="100">Qty</th>
                <th width="120">Subtotal</th>
            </tr>
        </thead>

        <tbody>

        @foreach($order->items as $item)

            <tr>
                <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                <td>${{ number_format($item->price,2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->subtotal,2) }}</td>
            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="text-end">
        <h4>
            Grand Total:
            ${{ number_format($order->total_amount,2) }}
        </h4>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">
        ← Back to My Orders
    </a>

</div>

@endsection