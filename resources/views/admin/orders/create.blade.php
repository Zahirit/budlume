@extends('admin.layouts.app')

@php
    $title = 'Create Test Order';
@endphp

@section('content')
<div class="card-box">
    <h2 class="mb-4">Create Test Order</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orders.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Customer Name *</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone *</label>
            <input type="text"
                   name="phone"
                   class="form-control"
                   value="{{ old('phone') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text"
                   name="address"
                   class="form-control"
                   value="{{ old('address') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Total Amount *</label>
            <input type="number"
                   name="total_amount"
                   step="0.01"
                   min="0"
                   class="form-control"
                   value="{{ old('total_amount') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            Create Test Order
        </button>

        <a href="{{ route('admin.orders.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </form>
</div>
@endsection