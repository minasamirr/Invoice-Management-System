@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Invoices</h2>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
        @endif
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Tax</th>
            <th>Total</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>
                        @if($invoice->customer)
                        {{ $invoice->customer->name }}<br>
                        <small>{{ $invoice->customer->email }}</small>
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</td>
                    <td>{{ number_format($invoice->tax, 2) }} {{ $invoice->currency }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
                    <td>{{ $invoice->status }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @if(auth()->user()->role === 'admin')
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this invoice?')">
                            Delete
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
        {{ $invoices->links('vendor/pagination/simple-default') }}
    </div>

    <!-- Option to select per page -->
    <form action="{{ route('invoices.index') }}" method="GET">
        <label for="per_page">Invoices per page:</label>
        <select name="per_page" id="per_page" onchange="this.form.submit()">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
    </form>
    <br>
@endsection
