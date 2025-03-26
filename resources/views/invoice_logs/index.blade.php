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
    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
        {{ $invoiceLogs->links('vendor/pagination/simple-default') }}
    </div>

    <!-- Option to select per page -->
    <form action="{{ route('invoice_logs.index') }}" method="GET">
        <label for="per_page">Invoices per page:</label>
        <select name="per_page" id="per_page" onchange="this.form.submit()">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
    </form>
    <br>
</div>

@endsection
