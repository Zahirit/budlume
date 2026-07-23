@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Delivery Partner Application
            </h2>

            <p class="text-muted mb-0">
                Review delivery partner information and verification documents.
            </p>
        </div>

        <a
            href="{{ route('admin.delivery-men.index') }}"
            class="btn btn-secondary"
        >
            ← Back to Delivery Men
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="row g-4">

        {{-- LEFT SIDE --}}
        <div class="col-lg-7">


            {{-- Personal Information --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Personal Information</strong>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <strong>Full Name</strong>

                            <div class="mt-1">
                                {{ $deliveryMan->user?->name ?? 'N/A' }}
                            </div>
                        </div>


                        <div class="col-md-6">
                            <strong>Email Address</strong>

                            <div class="mt-1">
                                {{ $deliveryMan->user?->email ?? 'N/A' }}
                            </div>
                        </div>


                        <div class="col-md-6">
                            <strong>Mobile Number</strong>

                            <div class="mt-1">
                                {{ $deliveryMan->user?->phone ?? 'N/A' }}
                            </div>
                        </div>


                        <div class="col-md-6">

                            <strong>Mobile Verification</strong>

                            <div class="mt-1">

                                @if($deliveryMan->user?->phone_verified_at)

                                    <span class="badge bg-success">
                                        Verified
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Not Verified
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <strong>Registered</strong>

                            <div class="mt-1">

                                {{ $deliveryMan->created_at
                                    ?->format('M d, Y h:i A') ?? 'N/A' }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <strong>Account Role</strong>

                            <div class="mt-1">
                                <span class="badge bg-dark">
                                    Delivery
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Verification Documents --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Verification Documents</strong>
                </div>

                <div class="card-body">

                    {{-- Driving License Number --}}
                    <div class="mb-4">

                        <strong>
                            Driving License Number
                        </strong>

                        <div class="mt-2">

                            {{ $deliveryMan->driving_license_number
                                ?? 'Not provided' }}

                        </div>

                    </div>


                    {{-- Driving License Photo --}}
                    <div class="mb-4">

                        <strong>
                            Driving License Photo
                        </strong>

                        <div class="mt-3">

                            @if($deliveryMan->driving_license_photo)

                                <a
                                    href="{{ asset(
                                        'storage/' .
                                        $deliveryMan->driving_license_photo
                                    ) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >

                                    <img
                                        src="{{ asset(
                                            'storage/' .
                                            $deliveryMan->driving_license_photo
                                        ) }}"
                                        alt="Driving License"
                                        style="
                                            max-width: 100%;
                                            max-height: 420px;
                                            border: 1px solid #ddd;
                                            border-radius: 8px;
                                            padding: 5px;
                                        "
                                    >

                                </a>

                                <div class="mt-2">

                                    <a
                                        href="{{ asset(
                                            'storage/' .
                                            $deliveryMan->driving_license_photo
                                        ) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View Full License Photo
                                    </a>

                                </div>

                            @else

                                <span class="text-muted">
                                    No driving license photo provided.
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- SIN --}}
                    <div>

                        <strong>SIN Number</strong>

                        <div class="mt-2">

                            @if($deliveryMan->sin_number)

                                @php
                                    $sinDigits = preg_replace(
                                        '/\D/',
                                        '',
                                        $deliveryMan->sin_number
                                    );

                                    $lastFour = substr(
                                        $sinDigits,
                                        -4
                                    );
                                @endphp

                                <span class="badge bg-secondary fs-6">
                                    *****{{ $lastFour }}
                                </span>

                            @else

                                <span class="text-muted">
                                    Not provided
                                </span>

                            @endif

                        </div>

                        <small class="text-muted d-block mt-2">
                            SIN is encrypted and only a masked value
                            is displayed for security.
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- RIGHT SIDE --}}
        <div class="col-lg-5">


            {{-- Application Status --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Application Status</strong>
                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <strong>Current Status</strong>

                        <div class="mt-2">

                            @if(
                                $deliveryMan->approval_status
                                === 'approved'
                            )

                                <span class="badge bg-success fs-6">
                                    Approved
                                </span>

                            @elseif(
                                $deliveryMan->approval_status
                                === 'rejected'
                            )

                                <span class="badge bg-danger fs-6">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-warning text-dark fs-6">
                                    Pending Admin Approval
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="mb-4">

                        <strong>Availability</strong>

                        <div class="mt-2">

                            @if($deliveryMan->is_available)

                                <span class="badge bg-success">
                                    Available
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Not Available
                                </span>

                            @endif

                        </div>

                    </div>


                    @if($deliveryMan->approved_at)

                        <div class="mb-3">

                            <strong>Approved At</strong>

                            <div class="mt-1">

                                {{ $deliveryMan->approved_at
                                    ->format('M d, Y h:i A') }}

                            </div>

                        </div>

                    @endif


                    @if($deliveryMan->approvedBy)

                        <div class="mb-3">

                            <strong>Approved By</strong>

                            <div class="mt-1">

                                {{ $deliveryMan
                                    ->approvedBy
                                    ->name }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Admin Actions --}}
            <div class="card shadow-sm">

                <div class="card-header bg-white">
                    <strong>Admin Actions</strong>
                </div>

                <div class="card-body">


                    @if(
                        $deliveryMan->approval_status
                        !== 'approved'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.delivery-men.approve',
                                $deliveryMan
                            ) }}"
                            class="mb-3"
                            onsubmit="return confirm(
                                'Approve this delivery partner?'
                            );"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                            >
                                ✓ Approve Delivery Partner
                            </button>

                        </form>

                    @endif


                    @if(
                        $deliveryMan->approval_status
                        !== 'rejected'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.delivery-men.reject',
                                $deliveryMan
                            ) }}"
                            onsubmit="return confirm(
                                'Reject this delivery partner application?'
                            );"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-danger w-100"
                            >
                                ✕ Reject Application
                            </button>

                        </form>

                    @endif


                    @if(
                        $deliveryMan->approval_status
                        === 'approved'
                    )

                        <div class="alert alert-success mt-3 mb-0">

                            This delivery partner is approved
                            and can access the delivery system.

                        </div>

                    @elseif(
                        $deliveryMan->approval_status
                        === 'rejected'
                    )

                        <div class="alert alert-danger mt-3 mb-0">

                            This delivery partner application
                            has been rejected.

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection