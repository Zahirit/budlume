@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')

<section class="checkout-section">

    <div class="checkout-container">

        <h1>Checkout</h1>
        <p>Complete your order details below.</p>

        <form method="POST" action="{{ route('checkout.store') }}">
    @csrf

        <div class="checkout-grid">
        

            {{-- Billing Details --}}
            <div class="checkout-billing">
                <h2>Billing Details</h2>

                <div class="checkout-field">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', Auth::user()->name) }}"
                        required
                    >
                </div>

                <div class="checkout-field">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', Auth::user()->email) }}"
                        required
                    >
                </div>

                <div class="checkout-field">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                    >
                </div>

                <div class="checkout-field">
                    <label>Delivery Address</label>
                    <textarea
                        name="address"
                        rows="4"
                        required
                    >{{ old('address') }}</textarea>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="checkout-summary">

                <h2>Your Order</h2>

                @php $total = 0; @endphp

                @foreach($cart as $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp

                    <div class="checkout-product">
                        <span>
                            {{ $item['name'] }}
                            × {{ $item['quantity'] }}
                        </span>

                        <strong>
                            ${{ number_format($subtotal, 2) }}
                        </strong>
                    </div>
                @endforeach

                <div class="checkout-total">
                    <span>Total</span>
                    <strong>${{ number_format($total, 2) }}</strong>
                </div>

                <button type="submit" class="checkout-order-btn">
                    Place Order
                </button>

            </div>

        </div>
        </form>

    </div>

</section>

@endsection