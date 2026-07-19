@extends('frontend.layouts.app')

@section('title', 'Change Password | Budlume')

@section('robots', 'noindex, nofollow')

@section('content')

<section class="customer-dashboard">

    <div class="customer-dashboard-container">

        <div class="customer-dashboard-header">

            <span class="dashboard-small-title">
                MY ACCOUNT
            </span>

            <h1>Change Password</h1>

            <p>
                Update your account password securely.
            </p>

        </div>


        <div class="customer-dashboard-layout">

            {{-- LEFT SIDEBAR --}}
            <aside class="customer-sidebar">

                <div class="customer-profile-box">

                    <div class="customer-avatar">

                        @if(Auth::user()->profile_photo)

                            <img
                                src="{{ asset('uploads/profile/' . Auth::user()->profile_photo) }}"
                                alt="{{ Auth::user()->name }}"
                            >

                        @else

                            <div class="customer-avatar-placeholder">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                        @endif

                    </div>

                    <h3>{{ Auth::user()->name }}</h3>


                    <span class="customer-role">
                        {{ strtoupper(Auth::user()->role ?? 'customer') }}
                    </span>


                    <p>{{ Auth::user()->email }}</p>

                </div>


                <nav class="customer-dashboard-menu">

                    <a href="{{ route('account.dashboard') }}">

                        <i class="bi bi-grid"></i>

                        <span>Dashboard</span>

                    </a>


                    <a href="{{ route('orders.index') }}">

                        <i class="bi bi-bag"></i>

                        <span>My Orders</span>

                    </a>


                    <a href="{{ route('profile.edit') }}">

                        <i class="bi bi-person"></i>

                        <span>Account Details</span>

                    </a>


                    <a href="{{ route('account.password') }}"
                       class="active">

                        <i class="bi bi-lock"></i>

                        <span>Change Password</span>

                    </a>


                    <a href="{{ route('shop') }}">

                        <i class="bi bi-shop"></i>

                        <span>Continue Shopping</span>

                    </a>


                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit">

                            <i class="bi bi-box-arrow-right"></i>

                            <span>Logout</span>

                        </button>

                    </form>

                </nav>

            </aside>


            {{-- PASSWORD CONTENT --}}
            <div class="customer-dashboard-content">

                <div class="customer-orders-box">

                    <div class="customer-orders-heading">

                        <div>

                            <span class="dashboard-small-title">
                                ACCOUNT SECURITY
                            </span>

                            <h2>Change Password</h2>

                            <p>
                                Use a strong password that you don't use
                                on other websites.
                            </p>

                        </div>

                    </div>


                    <form method="POST"
                          action="{{ route('password.update') }}"
                          class="customer-password-form">

                        @csrf
                        @method('PUT')


                        {{-- Current Password --}}
                        <div class="customer-form-group">

                            <label for="update_password_current_password">
                                Current Password
                            </label>

                            <input
                                id="update_password_current_password"
                                type="password"
                                name="current_password"
                                autocomplete="current-password"
                                required
                            >

                            @error('current_password', 'updatePassword')
                                <div class="customer-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- New Password --}}
                        <div class="customer-form-group">

                            <label for="update_password_password">
                                New Password
                            </label>

                            <input
                                id="update_password_password"
                                type="password"
                                name="password"
                                autocomplete="new-password"
                                required
                            >

                            @error('password', 'updatePassword')
                                <div class="customer-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Confirm Password --}}
                        <div class="customer-form-group">

                            <label for="update_password_password_confirmation">
                                Confirm New Password
                            </label>

                            <input
                                id="update_password_password_confirmation"
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password"
                                required
                            >

                            @error('password_confirmation', 'updatePassword')
                                <div class="customer-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="customer-password-actions">

                            <button type="submit"
                                    class="customer-shop-btn">

                                UPDATE PASSWORD

                                <i class="bi bi-shield-check"></i>

                            </button>

                        </div>


                        @if(session('status') === 'password-updated')

                            <div class="customer-password-success">

                                <i class="bi bi-check-circle"></i>

                                Password updated successfully.

                            </div>

                        @endif

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection