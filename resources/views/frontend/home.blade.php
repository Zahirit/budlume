@extends('frontend.layouts.app')

@section('title', 'Budlume')

@section('content')

<section class="hero-section">

    <img src="{{ asset('uploads/frontend/budlume-hero-bright.png') }}"
         alt="Budlume Premium Cannabis Products"
         class="hero-image">

    <div class="hero-overlay">
        <div class="hero-content">

            <div class="hero-small-title">
                Natural • Premium • Quality
            </div>

            <h1>
                Premium Cannabis<br>
                For A Better You
            </h1>

            <p>
                Discover premium quality cannabis products<br>
                crafted for your wellness and relaxation.
            </p>

            <a href="{{ route('shop') }}" class="hero-shop-btn">
                SHOP NOW <span>→</span>
            </a>

        </div>
    </div>

</section>
<section class="home-feature-section">
    <div class="home-feature-image">
         <img
        src="{{ asset('uploads/frontend/budlume-hero-bright.png') }}"
        alt="Budlume"
        class="hero-image"
    >
    </div>

    <div class="home-feature-content">
        <h2>Premium Quality Cannabis</h2>
        <p>
            Discover our selection of premium cannabis products,
            carefully selected for quality and freshness.
        </p>

        <a href="#" class="home-shop-btn">
            SHOP NOW
        </a>
    </div>
</section>
<section class="home-feature-section home-feature-reverse">

    <div class="home-feature-content">
        <h2>Cannabis Concentrates</h2>

        <p>
            Explore our selection of premium cannabis concentrates,
            offering quality and a wide variety of choices.
        </p>

        <a href="#" class="home-shop-btn">SHOP NOW</a>
    </div>

    <div class="home-feature-image">
        <img src="{{ asset('uploads/frontend/cannabis-Concentrates.jpg') }}"
             alt="Cannabis Concentrates">
    </div>

</section>
<section class="home-feature-section">

    <div class="home-feature-image">
        <img src="{{ asset('uploads/frontend/Disposable-Vape-Pens.webp') }}"
             alt="Disposable Vape Pens">
    </div>

    <div class="home-feature-content">
        <h2>Disposable Vape Pens</h2>

        <p>
            Discover our selection of disposable vape pens,
            offering convenience and a variety of choices.
        </p>

        <a href="#" class="home-shop-btn">SHOP NOW</a>
    </div>

</section>
<section class="home-products-section">
    <div class="home-products-heading">
        <h2>OUR PRODUCTS</h2>

        <div class="product-tabs">
            <button class="active">NEW PRODUCTS</button>
            <button>BUDS AND FLOWER</button>
            <button>CONCENTRATES</button>
            <button>EDIBLES</button>
        </div>
    </div>
     <div class="home-products-grid">
        @forelse($products as $product)
            <div class="home-product-card">

        <div class="home-product-image">
            @if($product->featured_image)
                <a href="/product/{{ $product->id }}">
                    <img src="{{ asset('uploads/products/' . $product->featured_image) }}"
                         alt="{{ $product->name }}">
                </a>
            @endif
        </div>

        <h3 class="home-product-title">
            <a href="/product/{{ $product->id }}">
                {{ $product->name }}
            </a>
        </h3>

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