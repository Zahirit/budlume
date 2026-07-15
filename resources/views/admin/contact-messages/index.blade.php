@extends('admin.layouts.app')

@php
    $title = 'Contact Messages';
@endphp

@section('content')
<div class="card-box">

    <h2 class="mb-4">Contact Messages</h2>

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