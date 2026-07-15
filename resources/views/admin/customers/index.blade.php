@extends('admin.layouts.app')

@php
    $title = 'Customers';
@endphp

@section('content')
<div class="card-box">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Customer List</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Total Orders</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email ?? 'N/A' }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->address ?? 'N/A' }}</td>
                        <td>{{ $customer->orders()->count() }}</td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                               class="btn btn-primary btn-sm">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                       <td colspan="8" class="text-center">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $customers->links() }}

</div>
@endsection