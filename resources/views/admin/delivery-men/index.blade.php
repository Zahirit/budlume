@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Delivery Man Management</h2>
            <p class="text-muted mb-0">
                Review and manage delivery partner applications.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Status Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-warning h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Pending Applications
                    </h6>

                    <h2 class="mb-0">
                        {{ $pendingCount }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-success h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Approved Delivery Partners
                    </h6>

                    <h2 class="mb-0">
                        {{ $approvedCount }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-danger h-100">
                <div class="card-body">
                    <h6 class="text-muted">
                        Rejected Applications
                    </h6>

                    <h2 class="mb-0">
                        {{ $rejectedCount }}
                    </h2>
                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex gap-2 flex-wrap">

                <a
                    href="{{ route('admin.delivery-men.index') }}"
                    class="btn {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}"
                >
                    All
                </a>

                <a
                    href="{{ route('admin.delivery-men.index', ['status' => 'pending']) }}"
                    class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}"
                >
                    Pending
                </a>

                <a
                    href="{{ route('admin.delivery-men.index', ['status' => 'approved']) }}"
                    class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}"
                >
                    Approved
                </a>

                <a
                    href="{{ route('admin.delivery-men.index', ['status' => 'rejected']) }}"
                    class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}"
                >
                    Rejected
                </a>

            </div>

        </div>

    </div>


    {{-- Delivery Men Table --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <strong>Delivery Partners</strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>License Number</th>
                            <th>Status</th>
                            <th>Availability</th>
                            <th>Registered</th>
                            <th class="text-end">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($deliveryMen as $deliveryMan)

                        <tr>

                            <td>
                                #{{ $deliveryMan->id }}
                            </td>

                            <td>
                                <strong>
                                    {{ $deliveryMan->user?->name ?? 'N/A' }}
                                </strong>
                            </td>

                            <td>
                                {{ $deliveryMan->user?->email ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $deliveryMan->user?->phone ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $deliveryMan->driving_license_number ?? 'N/A' }}
                            </td>

                            <td>

                                @if($deliveryMan->approval_status === 'approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($deliveryMan->approval_status === 'rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($deliveryMan->is_available)

                                    <span class="badge bg-success">
                                        Available
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Not Available
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $deliveryMan->created_at?->format('M d, Y') }}
                            </td>

                            <td class="text-end">

                                <a
                                    href="{{ route(
                                        'admin.delivery-men.show',
                                        $deliveryMan
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    View Application
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5 text-muted"
                            >
                                No delivery partner applications found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($deliveryMen->hasPages())

            <div class="card-footer bg-white">
                {{ $deliveryMen->links() }}
            </div>

        @endif

    </div>

</div>

@endsection