@extends('admin.layouts.app')

@php
    $title = 'Contact Messages';
@endphp

@section('content')
<div class="card-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">Contact Messages</h2>

        <small class="text-muted">
            Total Messages:
            <strong>{{ $messages->total() }}</strong>
        </small>
    </div>

    <form method="GET"
          action="{{ route('admin.contact-messages.index') }}"
          class="d-flex">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control me-2"
               placeholder="Search messages...">

        <button type="submit"
                class="btn btn-primary">
            Search
        </button>

        @if(request('search'))
            <a href="{{ route('admin.contact-messages.index') }}"
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>{{ $message->id }}</td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email ?? 'N/A' }}</td>
                        <td>{{ $message->phone ?? 'N/A' }}</td>
                        <td>{{ $message->subject ?? 'N/A' }}</td>
                        <td>
                            {{ $message->created_at ? $message->created_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                        <td>
                            <a href="{{ route('admin.contact-messages.show', $message) }}"
                               class="btn btn-primary btn-sm">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No contact messages found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $messages->links() }}

</div>
@endsection