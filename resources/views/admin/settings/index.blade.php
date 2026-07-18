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