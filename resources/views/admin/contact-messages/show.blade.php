@extends('admin.layouts.app')

@php
    $title = 'Contact Message Details';
@endphp

@section('content')
<div class="card-box">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contact Message Details</h2>

        <a href="{{ route('admin.contact-messages.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="mb-3">
        <strong>Name:</strong>
        {{ $contactMessage->name }}
    </div>

    <div class="mb-3">
        <strong>Email:</strong>

        @if($contactMessage->email)
            <a href="mailto:{{ $contactMessage->email }}">
                {{ $contactMessage->email }}
            </a>
        @else
            N/A
        @endif
        </div>

   <div class="mb-3">
        <strong>Phone:</strong>

        @if($contactMessage->phone)
            <a href="tel:{{ $contactMessage->phone }}">
                {{ $contactMessage->phone }}
            </a>
        @else
            N/A
        @endif
        </div>

    <div class="mb-3">
        <strong>Subject:</strong>
        {{ $contactMessage->subject ?? 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Date:</strong>
        {{ $contactMessage->created_at
        ? $contactMessage->created_at->format('d M Y h:i A')
        : 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Message:</strong>

        <div class="border rounded p-3 mt-2">
            {!! nl2br(e($contactMessage->message)) !!}
        </div>
    </div>

</div>
@endsection