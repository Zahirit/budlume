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
        <form action="{{ route('admin.orders.sendInvoice', $order) }}"
              method="POST"
              class="d-inline"
              style="margin:0;">
            @csrf

            <button type="submit" class="btn btn-primary">
                ✉ Send Invoice Email
            </button>
        </form>
        
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

                        @php
                            // New checkout snapshot data first.
                            // Fall back to old customer relationship
                            // for previous registered orders.

                            $displayName = $order->customer_name
                                ?: optional($order->customer)->name;

                            $displayEmail = $order->customer_email
                                ?: optional($order->customer)->email;

                            $displayPhone = $order->customer_phone
                                ?: optional($order->customer)->phone;

                            $oldAddress = optional($order->customer)->address;
                        @endphp


                        <p>
                            <strong>Customer Type:</strong>

                            @if($order->customer_type === 'guest')

                                <span class="badge bg-warning text-dark">
                                    Guest
                                </span>

                            @else

                                <span class="badge bg-success">
                                    Registered
                                </span>

                            @endif
                        </p>


                        <p>
                            <strong>Name:</strong>
                            {{ $displayName ?: 'N/A' }}
                        </p>


                        <p>
                            <strong>Email:</strong>
                            {{ $displayEmail ?: 'N/A' }}
                        </p>


                        <p>
                            <strong>Phone:</strong>
                            {{ $displayPhone ?: 'N/A' }}
                        </p>


                        <p>
                            <strong>Mobile Verified:</strong>

                            @if($order->phone_verified_at)

                                <span class="badge bg-success">
                                    Yes
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    No
                                </span>

                            @endif
                        </p>


                        <hr>


                        <p class="mb-1">
                            <strong>Delivery Address:</strong>
                        </p>


                        @if($order->delivery_address_line_1)

                            <div>

                                {{ $order->delivery_address_line_1 }}

                                @if($order->delivery_address_line_2)
                                    <br>
                                    {{ $order->delivery_address_line_2 }}
                                @endif

                                @if($order->delivery_city)
                                    <br>
                                    {{ $order->delivery_city }}
                                @endif

                                @if($order->delivery_state)
                                    , {{ $order->delivery_state }}
                                @endif

                                @if($order->delivery_postal_code)
                                    {{ $order->delivery_postal_code }}
                                @endif

                                @if($order->delivery_country)
                                    <br>
                                    {{ $order->delivery_country }}
                                @endif

                            </div>

                        @else

                            <p>
                                {{ $oldAddress ?: 'N/A' }}
                            </p>

                        @endif

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