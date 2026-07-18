@extends('admin.layouts.app')

@php
    $title = 'Customers';
@endphp

@section('content')

<div class="card-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Customer List</h2>

            <small class="text-muted">
                Total Customers:
                <strong>{{ $customers->total() }}</strong>
            </small>
        </div>

        <form action="{{ route('admin.customers.index') }}"
              method="GET"
              class="d-flex">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control me-2"
                   placeholder="Search customer...">

            <button type="submit"
                    class="btn btn-primary">
                Search
            </button>

            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-secondary ms-2">
                    Clear
                </a>
            @endif

        </form>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Orders</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($customers as $customer)

                    <tr>

                        <td>
                            {{ $customer->id }}
                        </td>

                        <td>
                            <strong>
                                {{ $customer->name }}
                            </strong>

                            @if($customer->address)
                                <br>

                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($customer->address, 35) }}
                                </small>
                            @endif
                        </td>

                        <td>
                            {{ $customer->email ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $customer->phone ?? 'N/A' }}
                        </td>

                        <td>
                            <span class="badge bg-primary">
                                {{ $customer->orders_count }}
                            </span>
                        </td>

                        <td>
                            <strong>
                                ${{ number_format(
                                    $customer->orders_sum_total_amount ?? 0,
                                    2
                                ) }}
                            </strong>
                        </td>

                        <td>
                            {{ $customer->created_at->format('d M Y') }}
                        </td>

                        <td>
                            <a href="{{ route('admin.customers.show', $customer) }}"
                               class="btn btn-primary btn-sm">
                                👁 View
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8"
                            class="text-center py-4">

                            @if(request('search'))
                                No customers found for
                                "<strong>{{ request('search') }}</strong>".
                            @else
                                No customers found.
                            @endif

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-3">
        {{ $customers->links() }}
    </div>

</div>

@endsection