@extends('admin.layouts.app')

@php
    $title = 'Admin Dashboard';
@endphp

@section('content')
    <div class="card-box">
        <h2 class="mb-3">🚗 CitiCarCanada Admin Dashboard</h2>
        <p class="mb-0">
            Welcome <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>,
            Laravel Admin Panel is working successfully.
        </p>
    </div>
@endsection