@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')

<section class="cart-page">

    <div class="cart-container">

        <div class="cart-page-header">
            <h1>Shopping Cart</h1>
            <p>Review and update your items before checkout.</p>
        </div>

        @if(count($cart) > 0)

            <div class="cart-table-wrapper">

                <table class="cart-table">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                        @php $total = 0; @endphp

                        @foreach($cart as $id => $item)

                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp

                            <tr>

                                <td>
                                    <div class="cart-product">

                                        @if(!empty($item['image']))
                                            <img
                                                src="{{ asset('uploads/products/' . $item['image']) }}"
                                                alt="{{ $item['name'] }}"
                                            >
                                        @endif

                                        <div>
                                            <h3>{{ $item['name'] }}</h3>
                                        </div>

                                    </div>
                                </td>

                                <td class="cart-price">
                                    ${{ number_format($item['price'], 2) }}
                                </td>

                                <td>
                                    <form
                                        action="{{ route('cart.update', $id) }}"
                                        method="POST"
                                        class="cart-update-form"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            class="cart-quantity"
                                        >

                                        <button type="submit" class="cart-update-btn">
                                            Update
                                        </button>

                                    </form>
                                </td>

                                <td class="cart-subtotal">
                                    ${{ number_format($subtotal, 2) }}
                                </td>

                                <td>
                                    <form
                                        action="{{ route('cart.remove', $id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="cart-remove-btn">
                                            Remove
                                        </button>

                                    </form>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="cart-bottom">

                <a href="{{ route('shop') }}" class="cart-continue-btn">
                    ← Continue Shopping
                </a>

                <div class="cart-summary">

                    <h2>Cart Total</h2>

                    <div class="cart-summary-row">
                        <span>Total</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>

                        <a href="{{ route('checkout') }}" class="cart-checkout-btn">
                            Proceed to Checkout
                        </a>

                </div>

            </div>

        @else

            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>

                <h2>Your cart is empty</h2>

                <p>You haven't added any products to your cart yet.</p>

                <a href="{{ route('shop') }}" class="cart-shop-btn">
                    Continue Shopping
                </a>
            </div>

        @endif

    </div>

</section>

@endsection