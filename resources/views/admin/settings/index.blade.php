@extends('admin.layouts.app')

@php
    $title = 'Settings';
@endphp

@section('content')

<div class="card-box">

    <div class="mb-4">
        <h2 class="mb-1">Website Settings</h2>
        <small class="text-muted">
            Manage your website information, logo and social links.
        </small>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- General Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>General Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site Name</label>

                        <input type="text"
                               name="site_name"
                               class="form-control"
                               value="{{ old('site_name', $setting->site_name ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo</label>

                        <input type="file"
                               name="logo"
                               class="form-control"
                               accept=".jpg,.jpeg,.png,.webp">

                        @if(!empty($setting->logo))
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">
                                    Current Logo
                                </small>

                                <img src="{{ asset('uploads/settings/' . $setting->logo) }}"
                                     alt="Website Logo"
                                     class="img-thumbnail"
                                     style="max-height: 80px;">
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- Contact Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>Contact Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $setting->phone ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $setting->email ?? '') }}">
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>

                    <textarea name="address"
                              class="form-control"
                              rows="3">{{ old('address', $setting->address ?? '') }}</textarea>
                </div>

            </div>

        </div>


        {{-- Store Location --}}
<div class="card shadow-sm mb-4">

    <div class="card-header">
        <strong>📍 Store Location</strong>
    </div>

    <div class="card-body">

        <p class="text-muted mb-3">
            These coordinates are used to find the nearest available
            delivery partner when assigning an order.
        </p>

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Store Latitude
                </label>

                <input type="number"
                       step="0.0000001"
                       min="-90"
                       max="90"
                       name="store_latitude"
                       id="store_latitude"
                       class="form-control"
                       placeholder="Example: 23.7833650"
                       value="{{ old(
                           'store_latitude',
                           $setting->store_latitude ?? ''
                       ) }}">

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Store Longitude
                </label>

                <input type="number"
                       step="0.0000001"
                       min="-180"
                       max="180"
                       name="store_longitude"
                       id="store_longitude"
                       class="form-control"
                       placeholder="Example: 90.4177930"
                       value="{{ old(
                           'store_longitude',
                           $setting->store_longitude ?? ''
                       ) }}">

            </div>

        </div>

        <button type="button"
                class="btn btn-outline-secondary"
                id="use-store-location">

            📍 Use Current Location

        </button>

        <small id="store-location-message"
               class="d-block mt-2 text-muted">
            You can enter the store coordinates manually or use
            this device's current GPS location.
        </small>

            </div>

        </div>

        {{-- Footer --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>Footer Settings</strong>
            </div>

            <div class="card-body">

                <label class="form-label">Footer Text</label>

                <textarea name="footer_text"
                          class="form-control"
                          rows="3">{{ old('footer_text', $setting->footer_text ?? '') }}</textarea>

            </div>

        </div>

        {{-- Social Media --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>Social Media Links</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Facebook URL</label>

                    <input type="text"
                           name="facebook"
                           class="form-control"
                           placeholder="https://facebook.com/..."
                           value="{{ old('facebook', $setting->facebook ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Instagram URL</label>

                    <input type="text"
                           name="instagram"
                           class="form-control"
                           placeholder="https://instagram.com/..."
                           value="{{ old('instagram', $setting->instagram ?? '') }}">
                </div>

                <div class="mb-0">
                    <label class="form-label">Twitter / X URL</label>

                    <input type="text"
                           name="twitter"
                           class="form-control"
                           placeholder="https://x.com/..."
                           value="{{ old('twitter', $setting->twitter ?? '') }}">
                </div>

            </div>

        </div>

        <div class="text-end">
            <button type="submit"
                    class="btn btn-primary px-4">
                Save Settings
            </button>
        </div>

    </form>

</div>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const button =
        document.getElementById('use-store-location');

    const latitude =
        document.getElementById('store_latitude');

    const longitude =
        document.getElementById('store_longitude');

    const message =
        document.getElementById('store-location-message');

    if (!button) {
        return;
    }

    button.addEventListener('click', function () {

        if (!navigator.geolocation) {

            message.textContent =
                'Geolocation is not supported by this browser.';

            return;
        }

        button.disabled = true;

        message.textContent =
            'Getting current store location...';

        navigator.geolocation.getCurrentPosition(

            function (position) {

                latitude.value =
                    position.coords.latitude.toFixed(7);

                longitude.value =
                    position.coords.longitude.toFixed(7);

                message.textContent =
                    '✓ Store location detected successfully.';

                button.disabled = false;
            },

            function (error) {

                message.textContent =
                    'Could not get location. Please allow location permission or enter coordinates manually.';

                button.disabled = false;
            },

            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }

        );

    });

});
</script>

@endpush