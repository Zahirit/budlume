@extends('admin.layouts.app')

@php
    $title = 'Settings';
@endphp

@section('content')
<div class="card-box">

    <h2 class="mb-4">Website Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Site Name</label>
            <input type="text"
                   name="site_name"
                   class="form-control"
                   value="{{ old('site_name', $setting->site_name ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file"
                   name="logo"
                   class="form-control">

            @if(!empty($setting->logo))
                <div class="mt-2">
                    <img src="{{ asset('uploads/settings/' . $setting->logo) }}"
                         alt="Logo"
                         style="max-height: 80px;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text"
                   name="phone"
                   class="form-control"
                   value="{{ old('phone', $setting->phone ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ old('email', $setting->email ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address"
                      class="form-control"
                      rows="3">{{ old('address', $setting->address ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Footer Text</label>
            <textarea name="footer_text"
                      class="form-control"
                      rows="3">{{ old('footer_text', $setting->footer_text ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Facebook URL</label>
            <input type="text"
                   name="facebook"
                   class="form-control"
                   value="{{ old('facebook', $setting->facebook ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Instagram URL</label>
            <input type="text"
                   name="instagram"
                   class="form-control"
                   value="{{ old('instagram', $setting->instagram ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Twitter URL</label>
            <input type="text"
                   name="twitter"
                   class="form-control"
                   value="{{ old('twitter', $setting->twitter ?? '') }}">
        </div>

        <button type="submit" class="btn btn-primary">
            Save Settings
        </button>

    </form>
</div>
@endsection