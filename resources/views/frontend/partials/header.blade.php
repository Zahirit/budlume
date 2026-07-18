<header class="site-header">
    <div class="header-container">

        {{-- Left Navigation --}}
        <nav class="main-nav">
            <a href="{{ route('home') }}">HOME</a>
            <a href="#">BLOG</a>
            <a href="#">CONTACT US</a>
            <a href="{{ route('shop') }}">SHOP</a>
        </nav>

        {{-- Center Logo --}}
        {{-- Center Logo --}}
        @php
            $setting = \App\Models\Setting::first();
        @endphp

        <div class="header-logo">
            <a href="{{ route('home') }}">
                @if($setting && $setting->logo)
                    <img src="{{ asset('uploads/settings/' . $setting->logo) }}"
                         alt="{{ $setting->site_name ?? 'Budlume' }}"
                         style="max-height: 60px; max-width: 220px;">
                @else
                    {{ $setting->site_name ?? 'Budlume' }}
                @endif
            </a>
        </div>

        {{-- Right Navigation --}}
        <div class="header-actions">
          @auth
    <div class="header-user-menu">

        <button type="button" class="header-user" onclick="toggleUserMenu()">
            @if(Auth::user()->profile_photo)
                <img
                    src="{{ asset('uploads/profile/' . Auth::user()->profile_photo) }}"
                    alt="{{ Auth::user()->name }}"
                    class="header-user-photo"
                >
            @endif

            <span>{{ Auth::user()->name }}</span>
            <span class="user-menu-arrow">▼</span>
        </button>

<div id="userDropdown" class="user-dropdown">

    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    {{-- Profile --}}
    <a href="{{ route('profile.edit') }}">
        <i class="bi bi-person-circle"></i>
        Profile
    </a>

    <hr>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </button>
    </form>

</div>

    </div>
@else
    <a href="{{ route('login') }}">MY ACCOUNT</a>
@endauth


            <a href="#" title="Search">⌕</a>
            <a href="#" title="Wishlist">♡</a>
            @php
                $cart = session('cart', []);
                $cartCount = collect($cart)->sum('quantity');
                $cartTotal = collect($cart)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });
            @endphp
                <div class="header-cart">
                <a href="{{ route('cart.index') }}" class="cart-icon" title="Cart">
                    <svg class="cart-svg" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 3h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H6"/>
                        <circle cx="10" cy="20" r="1.5"/>
                        <circle cx="18" cy="20" r="1.5"/>
                    </svg>
                    <span class="cart-count">{{ $cartCount }}</span>
                </a>

                <span class="cart-total">
                    ${{ number_format($cartTotal, 2) }}
                </span>
            </div>
        </div>

    </div>
</header>