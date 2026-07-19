@extends('frontend.layouts.app')

@section('title', 'Account Details | Budlume')

@section('robots', 'noindex, nofollow')

@section('content')

<section class="customer-dashboard customer-profile-page">

    <div class="customer-dashboard-container">

        {{-- PAGE HEADER --}}
        <div class="customer-dashboard-header">

            <span class="dashboard-small-title">
                MY ACCOUNT
            </span>

            <h1>Account Details</h1>

            <p>
                Manage your personal information, contact details
                and delivery address.
            </p>

        </div>


        <div class="customer-dashboard-layout">

            {{-- =====================================
                 LEFT SIDEBAR
            ====================================== --}}
            <aside class="customer-sidebar">

                <div class="customer-profile-box">

                    {{-- PROFILE PHOTO / AVATAR --}}
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


                    <h3>
                        {{ Auth::user()->name }}
                    </h3>


                    <span class="customer-role">

                        {{ strtoupper(Auth::user()->role ?? 'customer') }}

                    </span>


                    <p>
                        {{ Auth::user()->email }}
                    </p>

                </div>


                {{-- SIDEBAR MENU --}}
                <nav class="customer-dashboard-menu">

                    <a href="{{ route('account.dashboard') }}">

                        <i class="bi bi-grid"></i>

                        <span>Dashboard</span>

                    </a>


                    <a href="{{ route('orders.index') }}">

                        <i class="bi bi-bag"></i>

                        <span>My Orders</span>

                    </a>


                    <a href="{{ route('profile.edit') }}"
                       class="active">

                        <i class="bi bi-person"></i>

                        <span>Account Details</span>

                        <i class="bi bi-chevron-right menu-arrow"></i>

                    </a>


                    <a href="{{ route('account.password') }}">

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


            {{-- =====================================
                 ACCOUNT DETAILS CONTENT
            ====================================== --}}
            <div class="customer-dashboard-content">

                <div class="customer-account-card">

                    {{-- CARD HEADER --}}
                    <div class="customer-account-heading">

                        <span class="dashboard-small-title">
                            PERSONAL INFORMATION
                        </span>

                        <h2>Account Details</h2>

                        <p>
                            Keep your personal and contact information
                            accurate and up to date.
                        </p>

                    </div>


                    {{-- SUCCESS MESSAGE --}}
                    @if(session('status') === 'profile-updated')

                        <div class="customer-profile-success">

                            <i class="bi bi-check-circle"></i>

                            Profile updated successfully.

                        </div>

                    @endif


                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any())

                        <div class="customer-profile-errors">

                            <strong>
                                Please check the information below.
                            </strong>

                            <ul>

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- =====================================
                         PROFILE UPDATE FORM
                    ====================================== --}}
                    <form
                        method="POST"
                        action="{{ route('profile.update') }}"
                        enctype="multipart/form-data"
                        class="customer-account-form"
                    >

                        @csrf
                        @method('PATCH')


                        {{-- PROFILE PHOTO --}}
                        <div class="customer-profile-photo-section">

                            <div class="customer-profile-photo-preview">

                                @if(Auth::user()->profile_photo)

                                    <img
                                        src="{{ asset('uploads/profile/' . Auth::user()->profile_photo) }}"
                                        alt="{{ Auth::user()->name }}"
                                    >

                                @else

                                    <div class="profile-photo-placeholder">

                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                                    </div>

                                @endif

                            </div>


                            <div class="customer-profile-photo-info">

                                <h3>Profile Photo</h3>

                                <p>
                                    Upload a clear JPG, PNG or WEBP image.
                                </p>

                                <label class="customer-upload-btn">

                                    <i class="bi bi-camera"></i>

                                    Choose Photo

                                    <input
                                        type="file"
                                        name="profile_photo"
                                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    >

                                </label>

                                @error('profile_photo')

                                    <div class="customer-form-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        <div class="customer-form-divider"></div>


                        {{-- FORM GRID --}}
                        <div class="customer-account-form-grid">


                            {{-- FULL NAME --}}
                            <div class="customer-form-group">

                                <label for="name">

                                    Full Name

                                    <span class="required">*</span>

                                </label>

                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', Auth::user()->name) }}"
                                    required
                                    autocomplete="name"
                                >

                                @error('name')

                                    <div class="customer-form-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- PHONE --}}
                            <div class="customer-form-group">

                                <label for="phone">

                                    Phone Number

                                    <span class="required">*</span>

                                </label>


                                <div class="verified-input-wrap">

                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone', Auth::user()->phone) }}"
                                        placeholder="+880..."
                                        autocomplete="tel"
                                        required
                                    >


                                    @if(Auth::user()->phone_verified_at)

                                        <span class="verification-badge verified">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Verified

                                        </span>

                                    @else

                                        <span class="verification-badge unverified">

                                            <i class="bi bi-exclamation-circle"></i>

                                            Not Verified

                                        </span>

                                    @endif

                                </div>


                                @error('phone')

                                    <div class="customer-form-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- EMAIL --}}
                            <div class="customer-form-group customer-form-full">

                                <label for="email">

                                    Email Address

                                    <span class="required">*</span>

                                </label>


                                <div class="verified-input-wrap">

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email', Auth::user()->email) }}"
                                        required
                                        autocomplete="email"
                                    >


                                    @if(Auth::user()->email_verified_at)

                                        <span class="verification-badge verified">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Verified

                                        </span>

                                    @else

                                        <span class="verification-badge unverified">

                                            <i class="bi bi-exclamation-circle"></i>

                                            Not Verified

                                        </span>

                                    @endif

                                </div>


                                @error('email')

                                    <div class="customer-form-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ADDRESS LINE 1 --}}
                            <div class="customer-form-group customer-form-full">

                                <label for="address_line_1">

                                    Address Line 1

                                    <span class="required">*</span>

                                </label>

                                <input
                                    id="address_line_1"
                                    type="text"
                                    name="address_line_1"
                                    value="{{ old('address_line_1', Auth::user()->address_line_1) }}"
                                    placeholder="Street address, house or apartment"
                                    autocomplete="address-line1"
                                    required
                                >

                                @error('address_line_1')

                                    <div class="customer-form-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- ADDRESS LINE 2 --}}
                            <div class="customer-form-group customer-form-full">

                                <label for="address_line_2">
                                    Address Line 2
                                </label>

                                <input
                                    id="address_line_2"
                                    type="text"
                                    name="address_line_2"
                                    value="{{ old('address_line_2', Auth::user()->address_line_2) }}"
                                    placeholder="Apartment, suite, unit, etc. (optional)"
                                    autocomplete="address-line2"
                                >

                            </div>


                            {{-- CITY --}}
                            <div class="customer-form-group">

                                <label for="city">

                                    City

                                    <span class="required">*</span>

                                </label>

                                <input
                                    id="city"
                                    type="text"
                                    name="city"
                                    value="{{ old('city', Auth::user()->city) }}"
                                    autocomplete="address-level2"
                                    required
                                >

                            </div>


                            {{-- STATE --}}
                            <div class="customer-form-group">

                                <label for="state">
                                    State / Region
                                </label>

                                <input
                                    id="state"
                                    type="text"
                                    name="state"
                                    value="{{ old('state', Auth::user()->state) }}"
                                    autocomplete="address-level1"
                                >

                            </div>


                            {{-- POSTAL CODE --}}
                            <div class="customer-form-group">

                                <label for="postal_code">
                                    Postal Code
                                </label>

                                <input
                                    id="postal_code"
                                    type="text"
                                    name="postal_code"
                                    value="{{ old('postal_code', Auth::user()->postal_code) }}"
                                    autocomplete="postal-code"
                                >

                            </div>


                            {{-- COUNTRY --}}
                            <div class="customer-form-group">

                                <label for="country">

                                    Country

                                    <span class="required">*</span>

                                </label>

                                <input
                                    id="country"
                                    type="text"
                                    name="country"
                                    value="{{ old('country', Auth::user()->country) }}"
                                    autocomplete="country-name"
                                    required
                                >

                            </div>

                        </div>


                        {{-- SUBMIT --}}
                        <div class="customer-account-actions">

                            <button
                                type="submit"
                                class="customer-shop-btn"
                            >

                                UPDATE PROFILE

                                <i class="bi bi-check-circle"></i>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection