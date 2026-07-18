@extends('admin.layouts.app')

@php
    $title = 'Order Details';
@endphp

@section('content')

<div class="card-box">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Order #{{ $order->order_number }}</h2>

    <div>
        <a href="{{ route('admin.orders.invoice', $order) }}"
           class="btn btn-success"
           target="_blank">
            🖨 Print Invoice
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="btn btn-secondary">
            ← Back
        </a>
    </div>

</div>

    <div class="row">

        <!-- Customer Information -->
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm">

                <div class="card-header">
                    <strong>Customer Information</strong>
                </div>

                <div class="card-body">

                    <p><strong>Name:</strong> {{ $order->customer->name ?? 'N/A' }}</p>

                    <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>

                    <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>

                    <p><strong>Address:</strong> {{ $order->customer->address ?? 'N/A' }}</p>

                </div>

            </div>

        </div>

        <!-- Order Information -->
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm">

                <div class="card-header">
                    <strong>Order Information</strong>
                </div>

                <div class="card-body">

                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>

                    <p><strong>Date:</strong> {{ $order->created_at->format('d M Y h:i A') }}</p>

                    <p>
                        <strong>Status:</strong>

                        @php
                            $badge = match(strtolower($order->status)){
                                'pending'=>'warning',
                                'processing'=>'primary',
                                'completed'=>'success',
                                'cancelled'=>'danger',
                                default=>'secondary'
                            };
                        @endphp

                        <span class="badge bg-{{ $badge }}">
                            {{ $order->status }}
                        </span>

                    </p>

                    <p>
                        <strong>Total:</strong>

                        ${{ number_format($order->total_amount,2) }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Ordered Products -->

    <div class="card shadow-sm mt-3">

        <div class="card-header">

            <strong>Ordered Products</strong>

        </div>

        <div class="card-body p-0">

            <table class="table table-bordered mb-0">

                <thead>

                    <tr>

                        <th>Image</th>

                        <th>Product</th>

                        <th>Price</th>

                        <th>Qty</th>

                        <th>Subtotal</th>

                    </tr>

                </thead>

                <tbody>

                @foreach($order->items as $item)

                    <tr>

                        <td width="80">

                            @if($item->product && $item->product->featured_image)

                                <img
                                    src="{{ asset('uploads/products/'.$item->product->featured_image) }}"
                                    width="60"
                                    class="img-thumbnail">

                            @endif

                        </td>

                        <td>

                            {{ $item->product->name ?? 'Deleted Product' }}

                        </td>

                        <td>

                            ${{ number_format($item->price,2) }}

                        </td>

                        <td>

                            {{ $item->quantity }}

                        </td>

                        <td>

                            ${{ number_format($item->price * $item->quantity,2) }}

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <!-- Update Status -->

    <div class="card shadow-sm mt-4">

        <div class="card-header">

            <strong>Update Order Status</strong>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.orders.update',$order) }}">

                @csrf

                @method('PUT')

                <div class="row">

                    <div class="col-md-4">

                        <select
                            name="status"
                            class="form-select">

                            <option {{ $order->status=='Pending'?'selected':'' }}>
                                Pending
                            </option>

                            <option {{ $order->status=='Processing'?'selected':'' }}>
                                Processing
                            </option>

                            <option {{ $order->status=='Completed'?'selected':'' }}>
                                Completed
                            </option>

                            <option {{ $order->status=='Cancelled'?'selected':'' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <button class="btn btn-success">

                            Update Status

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection