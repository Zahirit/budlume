@extends('frontend.layouts.app')

@section('title', 'Verify Mobile Number')

@section('content')

<section class="guest-otp-section">

    <div class="guest-otp-container">

        <div class="guest-otp-card">

            {{-- Icon --}}
            <div class="guest-otp-icon">
                ✓
            </div>

            <h1>Verify Your Mobile</h1>

            <p class="guest-otp-description">
                Before we confirm your order, please verify your mobile
                number using the 6-digit verification code.
            </p>

            {{-- Phone Number --}}
            <div class="guest-otp-phone">

                <span>Verification code sent to</span>

                <strong>
                    {{ session('guest_checkout.phone') }}
                </strong>

            </div>


            {{-- Success Message --}}
            @if(session('success'))

                <div class="guest-otp-alert guest-otp-alert-success">
                    {{ session('success') }}
                </div>

            @endif


            {{-- Error Message --}}
            @if(session('error'))

                <div class="guest-otp-alert guest-otp-alert-error">
                    {{ session('error') }}
                </div>

            @endif


            {{-- Resend OTP --}}
            <form
                method="POST"
                action="{{ route('guest.phone.otp.send') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="guest-otp-btn guest-otp-btn-resend"
                >
                    Resend Verification Code
                </button>

            </form>


            <div class="guest-otp-divider">
                Enter Verification Code
            </div>


            {{-- Verify OTP --}}
            <form
                method="POST"
                action="{{ route('guest.phone.otp.verify') }}"
                class="guest-otp-form"
            >
                @csrf

                <label
                    for="otp"
                    class="guest-otp-label"
                >
                    6-Digit Verification Code
                </label>

                <input
                    type="text"
                    id="otp"
                    name="otp"
                    class="guest-otp-input"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    required
                    autofocus
                >

                @error('otp')

                    <div
                        class="guest-otp-alert guest-otp-alert-error"
                        style="margin-top: 10px;"
                    >
                        {{ $message }}
                    </div>

                @enderror


                <button
                    type="submit"
                    class="guest-otp-btn guest-otp-btn-primary"
                >
                    Verify & Place Order
                </button>

            </form>


            <div class="guest-otp-back">

                <a href="{{ route('checkout') }}">
                    ← Back to Checkout
                </a>

            </div>


            <div class="guest-otp-security">
                Your mobile number is used only to verify and process
                your order securely.
            </div>

        </div>

    </div>

</section>

@endsection