@extends('frontend.layouts.app')

@section('title', $product->name . ' - Budlume')

@section('content')

<div class="breadcrumb-section">

    <a href="{{ url('/') }}">Home</a>

    <span>/</span>

    <a href="{{ route('shop') }}">Shop</a>

    <span>/</span>

    <span>{{ $product->name }}</span>

</div>

<section class="product-details-section">

    <div class="product-details-image">
        @if($product->featured_image)
            <img src="{{ asset('uploads/products/' . $product->featured_image) }}"
                 alt="{{ $product->name }}">
        @endif
    </div>

<div class="product-details-content">

    <h1>{{ $product->name }}</h1>

    <p class="product-category">
        <strong>Category:</strong>
        {{ $product->category->name ?? 'Uncategorized' }}
    </p>

    <p class="product-stock">
        <strong>Status:</strong>

        @if($product->stock > 0)
            <span style="color:green;">In Stock</span>
        @else
            <span style="color:red;">Out of Stock</span>
        @endif
    </p>

    <div class="product-details-price">

        @if($product->sale_price)

            <span class="old-price">
                ${{ number_format($product->price,2) }}
            </span>

            <strong>
                ${{ number_format($product->sale_price,2) }}
            </strong>

        @else

            <strong>
                ${{ number_format($product->price,2) }}
            </strong>

        @endif

    </div>

    <p class="product-short-description">
        {{ $product->short_description }}
    </p>

    <form action="{{ route('cart.add',$product) }}" method="POST">

        @csrf

        <div class="product-qty">

            <label>Quantity</label>

            <input
                type="number"
                name="quantity"
                value="1"
                min="1"
                max="{{ $product->stock }}"
            >

        </div>

        <button class="home-shop-btn">
            ADD TO CART
        </button>

    </form>

</div>

</section>

@endsection