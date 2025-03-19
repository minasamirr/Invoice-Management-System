@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Invoice Logs</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice Number</th>
                <th>User</th>
                <th>Action</th>
                <th>Role</th>
                <th>Details</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceLogs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>
                    @if($log->invoice)
                        {{ $log->invoice->invoice_number }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($log->user)
                        {{ $log->user->name }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ ucfirst($log->action) }}</td>
                <td>{{ ucfirst($log->role) }}</td>
                <td>{{ $log->details }}</td>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
