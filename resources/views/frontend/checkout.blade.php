@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')

<section class="checkout-section">

    <div class="checkout-container">

        <h1>Checkout</h1>

        @auth
            <p>
                Confirm your details and delivery address below.
                You may change the delivery address for this order.
            </p>
        @else
            <p>
                Checkout as a guest. No account or password is required.
                Your mobile number will be verified before the order is confirmed.
            </p>
        @endauth


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="checkout-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form
            method="POST"
            action="{{ route('checkout.store') }}"
        >

            @csrf

            <div class="checkout-grid">


                {{-- Customer / Delivery Details --}}
                <div class="checkout-billing">

                    <h2>
                        @auth
                            Customer & Delivery Details
                        @else
                            Guest & Delivery Details
                        @endauth
                    </h2>


                    {{-- Full Name --}}
                    <div class="checkout-field">

                        <label for="name">
                            Full Name
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"

                            value="{{ old(
                                'name',
                                auth()->check()
                                    ? auth()->user()->name
                                    : ''
                            ) }}"

                            required
                        >

                    </div>


                    {{-- Email --}}
                    <div class="checkout-field">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"

                            value="{{ old(
                                'email',
                                auth()->check()
                                    ? auth()->user()->email
                                    : ''
                            ) }}"

                            required
                        >

                    </div>


                    {{-- Phone --}}
                    <div class="checkout-field">

                        <label for="phone">
                            Mobile Number
                        </label>

                        <input
                            id="phone"
                            type="text"
                            name="phone"

                            value="{{ old(
                                'phone',
                                auth()->check()
                                    ? auth()->user()->phone
                                    : ''
                            ) }}"

                            required
                        >

                        @auth

                            @if (auth()->user()->phone_verified_at)

                                <small class="checkout-verified">
                                    ✓ Verified mobile number
                                </small>

                            @else

                                <small class="checkout-unverified">
                                    Mobile verification required before ordering.
                                </small>

                            @endif

                        @endauth

                    </div>


                    {{-- Delivery Address --}}
                    <h3 class="checkout-address-title">
                        Delivery Address
                    </h3>

                    @auth
                        <p class="checkout-address-note">
                            Your saved customer address is shown below.
                            You can edit it for this delivery without changing
                            your saved Account Details.
                        </p>
                    @else
                        <p class="checkout-address-note">
                            Enter the address where you want this order delivered.
                        </p>
                    @endauth


                    {{-- Address Line 1 --}}
                    <div class="checkout-field">

                        <label for="delivery_address_line_1">
                            Address Line 1
                        </label>

                        <input
                            id="delivery_address_line_1"
                            type="text"
                            name="delivery_address_line_1"

                            value="{{ old(
                                'delivery_address_line_1',
                                auth()->check()
                                    ? auth()->user()->address_line_1
                                    : ''
                            ) }}"

                            placeholder="House, road, street address"
                            required
                        >

                    </div>


                    {{-- Address Line 2 --}}
                    <div class="checkout-field">

                        <label for="delivery_address_line_2">
                            Apartment / Suite / Unit
                            <span>(Optional)</span>
                        </label>

                        <input
                            id="delivery_address_line_2"
                            type="text"
                            name="delivery_address_line_2"

                            value="{{ old(
                                'delivery_address_line_2',
                                auth()->check()
                                    ? auth()->user()->address_line_2
                                    : ''
                            ) }}"

                            placeholder="Apartment, suite, unit, floor"
                        >

                    </div>


                    {{-- City --}}
                    <div class="checkout-field">

                        <label for="delivery_city">
                            City
                        </label>

                        <input
                            id="delivery_city"
                            type="text"
                            name="delivery_city"

                            value="{{ old(
                                'delivery_city',
                                auth()->check()
                                    ? auth()->user()->city
                                    : ''
                            ) }}"

                            required
                        >

                    </div>


                    {{-- State --}}
                    <div class="checkout-field">

                        <label for="delivery_state">
                            State / Region
                        </label>

                        <input
                            id="delivery_state"
                            type="text"
                            name="delivery_state"

                            value="{{ old(
                                'delivery_state',
                                auth()->check()
                                    ? auth()->user()->state
                                    : ''
                            ) }}"
                        >

                    </div>


                    {{-- Postal Code --}}
                    <div class="checkout-field">

                        <label for="delivery_postal_code">
                            Postal Code
                        </label>

                        <input
                            id="delivery_postal_code"
                            type="text"
                            name="delivery_postal_code"

                            value="{{ old(
                                'delivery_postal_code',
                                auth()->check()
                                    ? auth()->user()->postal_code
                                    : ''
                            ) }}"

                            required
                        >

                    </div>


                    {{-- Country --}}
                    <div class="checkout-field">

                        <label for="delivery_country">
                            Country
                        </label>

                        <input
                            id="delivery_country"
                            type="text"
                            name="delivery_country"

                            value="{{ old(
                                'delivery_country',
                                auth()->check()
                                    ? auth()->user()->country
                                    : ''
                            ) }}"

                            required
                        >

                    </div>

                </div>


                {{-- Order Summary --}}
                <div class="checkout-summary">

                    <h2>Your Order</h2>

                    @php
                        $total = 0;
                    @endphp


                    @foreach ($cart as $item)

                        @php

                            $subtotal =
                                $item['price']
                                * $item['quantity'];

                            $total += $subtotal;

                        @endphp


                        <div class="checkout-product">

                            <span>

                                {{ $item['name'] }}

                                ×

                                {{ $item['quantity'] }}

                            </span>

                            <strong>
                                ${{ number_format($subtotal, 2) }}
                            </strong>

                        </div>

                    @endforeach


                    <div class="checkout-total">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            ${{ number_format($total, 2) }}
                        </strong>

                    </div>


                    @auth

                        <div class="checkout-customer-benefit">

                            <strong>
                                Customer Discount
                            </strong>

                            <p>
                                Eligible registered customers receive the
                                current customer discount when the program
                                is enabled.
                            </p>

                        </div>

                    @else

                        <div class="checkout-register-benefit">

                            <strong>
                                Want customer savings?
                            </strong>

                            <p>
                                Create an account to receive the current
                                customer discount on future eligible orders.
                            </p>

                            <a href="{{ route('register') }}">
                                Create an Account
                            </a>

                        </div>

                    @endauth


                    <div class="checkout-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            ${{ number_format($total, 2) }}
                        </strong>

                    </div>


                    <button
                        type="submit"
                        class="checkout-order-btn"
                    >

                        @auth
                            Place Order
                        @else
                            Continue to Mobile Verification
                        @endauth

                    </button>

                </div>

            </div>

        </form>

    </div>

</section>

@endsection