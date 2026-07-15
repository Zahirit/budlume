@extends('frontend.layouts.app')

@section('title', 'Order Successful')

@section('content')

<section class="order-success-section">
    <div class="order-success-box">

        <div class="order-success-icon">
            ✓
        </div>

        <h1>Thank You!</h1>

        <h2>Your order has been placed successfully.</h2>

        <p>Your Order Number:</p>

        <div class="order-number">
            {{ $order->order_number }}
        </div>

        <p class="order-success-total">
            Order Total:
            <strong>${{ number_format($order->total_amount, 2) }}</strong>
        </p>

        <p>
            We have received your order and will begin processing it shortly.
        </p>

        <a href="{{ route('shop') }}" class="order-success-btn">
            Continue Shopping
        </a>

    </div>
</section>

@endsection