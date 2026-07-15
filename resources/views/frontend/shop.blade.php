@extends('frontend.layouts.app')

@section('title', 'Shop - Budlume')

@section('content')

<section class="home-products-section">

    <div class="home-products-heading">
        <h2>SHOP</h2>
    </div>

    <div class="home-products-grid">
        @forelse($products as $product)

            <div class="home-product-card">

                <a href="{{ route('product.show', $product) }}">
                <div class="home-product-image">
                    @if($product->featured_image)
                        <img src="{{ asset('uploads/products/' . $product->featured_image) }}"
                             alt="{{ $product->name }}">
                    @endif
                </div>
            </a>

              <h5 class="product-name">
                    <a href="{{ route('product.show', $product) }}" class="product-title-link">
                        {{ $product->name }}
                    </a>
                </h5>

                <div class="home-product-price">
                    @if($product->sale_price)
                        <span class="old-price">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span>
                            ${{ number_format($product->sale_price, 2) }}
                        </span>
                    @else
                        <span>${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

            </div>

        @empty
            <p>No products available.</p>
        @endforelse
    </div>

</section>

@endsection