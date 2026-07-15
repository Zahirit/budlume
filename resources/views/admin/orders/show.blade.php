@extends('admin.layouts.app')

@php
    $title = 'Order Details';
@endphp

@section('content')

<div class="card-box">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Details</h2>

        <a href="{{ route('admin.orders.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4">
                <div class="card-header">
                    <strong>Order Information</strong>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Order Number:</strong>
                        {{ $order->order_number }}
                    </p>

                    <p>
                        <strong>Total Amount:</strong>
                        ${{ number_format($order->total_amount,2) }}
                    </p>

                    <p>
                        <strong>Status:</strong>

                        <span class="badge bg-info">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>

                    <p>
                        <strong>Date:</strong>
                        {{ $order->created_at->format('d M Y h:i A') }}
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Customer Information</strong>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Name:</strong>
                        {{ $order->customer->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Email:</strong>
                        {{ $order->customer->email ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Phone:</strong>
                        {{ $order->customer->phone ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Address:</strong><br>

                        {{ $order->customer->address ?? '' }}<br>

                        {{ $order->customer->city ?? '' }}
                        {{ $order->customer->state ?? '' }}
                        {{ $order->customer->zip_code ?? '' }}

                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">
            <strong>Update Order Status</strong>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.orders.update', $order) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4">

                        <select name="status"
                                class="form-select">

                            <option value="Pending"
                                {{ strtolower($order->status)=='pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Processing"
                                {{ strtolower($order->status)=='processing' ? 'selected' : '' }}>
                                Processing
                            </option>

                            <option value="Completed"
                                {{ strtolower($order->status)=='completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="Cancelled"
                                {{ strtolower($order->status)=='cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <button type="submit"
                                class="btn btn-success">

                            Update Status

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card">

        <div class="card-header">
            <strong>Ordered Products</strong>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($order->items as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->product->name ?? 'Product Deleted' }}
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

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center">

                                    No order items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    @if($order->notes)

        <div class="card mt-4">

            <div class="card-header">
                <strong>Order Notes</strong>
            </div>

            <div class="card-body">

                {{ $order->notes }}

            </div>

        </div>

    @endif

</div>

@endsection