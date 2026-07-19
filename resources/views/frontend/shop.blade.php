@extends('frontend.layouts.app')

@section('title', 'Shop Premium Products | Budlume')

@section(
    'meta_description',
    'Shop the Budlume collection of premium products. Explore our carefully selected range and discover quality products online.'
)

@section('canonical', route('shop'))

@section('og_title', 'Shop Premium Products | Budlume')

@section(
    'og_description',
    'Explore the Budlume collection of carefully selected premium products.'
)

@section(
    'og_image',
    asset('uploads/frontend/budlume-hero-bright.png')
)

@section('content')

<section class="shop-section">

    {{-- Shop Heading --}}
    <div class="shop-heading">

        <div class="shop-small-title">
            <span>—</span>
            🌿 OUR COLLECTION
            <span>—</span>
        </div>

        <h1>Shop Premium Products</h1>

        <p>
            Discover our carefully selected collection of premium products.
        </p>

    </div>


    {{-- Product Grid --}}
    <div class="shop-products-grid">

        @forelse($products as $product)

            <article class="shop-product-card">

                {{-- Product Image --}}
                <div class="shop-product-image">

                    @if($product->sale_price)
                        <span class="shop-sale-badge">
                            SALE
                        </span>
                    @endif

                    <a href="{{ route('product.show', $product) }}">

                        @if($product->featured_image)

                            <img
                                src="{{ asset('uploads/products/' . $product->featured_image) }}"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            >

                        @else

                            <div class="shop-no-image">
                                No Image
                            </div>

                        @endif

                    </a>

                    {{-- Hover Button --}}
                    <div class="shop-product-action">

                        <a href="{{ route('product.show', $product) }}"
                           class="shop-view-btn">

                            VIEW PRODUCT

                        </a>

                    </div>

                </div>


                {{-- Product Information --}}
                <div class="shop-product-info">

                    <h2 class="shop-product-name">

                        <a href="{{ route('product.show', $product) }}">

                            {{ $product->name }}

                        </a>

                    </h2>


                    <div class="shop-product-price">

                        @if($product->sale_price)

                            <span class="shop-old-price">

                                ${{ number_format($product->price, 2) }}

                            </span>

                            <span class="shop-sale-price">

                                ${{ number_format($product->sale_price, 2) }}

                            </span>

                        @else

                            <span class="shop-regular-price">

                                ${{ number_format($product->price, 2) }}

                            </span>

                        @endif

                    </div>

                </div>

            </article>

        @empty

            <div class="shop-empty">

                <h3>No products available.</h3>

                <p>Please check back soon for new products.</p>

            </div>

        @endforelse

    </div>

</section>

@endsection