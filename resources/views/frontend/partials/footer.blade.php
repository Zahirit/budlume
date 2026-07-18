@php
    $setting = \App\Models\Setting::first();
@endphp

<footer class="site-footer">
    <div class="footer-container">

        {{-- Website Information --}}
        <div class="footer-column">
            <h3>{{ $setting->site_name ?? 'Budlume' }}</h3>

            <p>
                {{ $setting->footer_text ?? 'Premium quality products with fast and reliable delivery.' }}
            </p>
        </div>

        {{-- Quick Links --}}
        <div class="footer-column">
            <h4>QUICK LINKS</h4>

            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="#">Contact Us</a>

            @auth
                <a href="{{ route('profile.edit') }}">My Account</a>
            @else
                <a href="{{ route('login') }}">My Account</a>
            @endauth
        </div>

        {{-- Contact Information --}}
        <div class="footer-column">
            <h4>CONTACT US</h4>

            @if(!empty($setting?->phone))
                <p>Phone: {{ $setting->phone }}</p>
            @endif

            @if(!empty($setting?->email))
                <p>Email: {{ $setting->email }}</p>
            @endif

            @if(!empty($setting?->address))
                <p>Address: {{ $setting->address }}</p>
            @endif

            {{-- Social Media --}}
            <div class="footer-social">
                @if(!empty($setting?->facebook))
                    <a href="{{ $setting->facebook }}" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                @endif

                @if(!empty($setting?->instagram))
                    <a href="{{ $setting->instagram }}" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                @endif

                @if(!empty($setting?->twitter))
                    <a href="{{ $setting->twitter }}" target="_blank">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                @endif
            </div>

        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }}
        {{ $setting->site_name ?? 'Budlume' }}.
        All Rights Reserved.
    </div>
</footer>