@extends('layouts.app')

@section('content')
    <h2>Advanced Invoice Search</h2>

    <form action="{{ route('invoices.search') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ request('invoice_number') }}">
            </div>
            <div class="col-md-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ request('customer_name') }}">
            </div>
            <div class="col-md-3">
                <label for="invoice_date_from" class="form-label">Invoice Date From</label>
                <input type="date" name="invoice_date_from" id="invoice_date_from" class="form-control" value="{{ request('invoice_date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="invoice_date_to" class="form-label">Invoice Date To</label>
                <input type="date" name="invoice_date_to" id="invoice_date_to" class="form-control" value="{{ request('invoice_date_to') }}">
            </div>
            <div class="col-md-3 mt-3">
                <label for="invoice_amount_from" class="form-label">Amount From</label>
                <input type="number" step="0.01" name="invoice_amount_from" id="invoice_amount_from" class="form-control" value="{{ request('invoice_amount_from') }}">
            </div>
            <div class="col-md-3 mt-3">
                <label for="invoice_amount_to" class="form-label">Amount To</label>
                <input type="number" step="0.01" name="invoice_amount_to" id="invoice_amount_to" class="form-control" value="{{ request('invoice_amount_to') }}">
            </div>
            <div class="col-md-3 mt-3">
                <label for="payment_status" class="form-label">Payment Status</label>
                <select name="payment_status" id="payment_status" class="form-select">
                    <option value="">--Select Status--</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('payment_status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label for="currency" class="form-label">Currency</label>
                <select name="currency" id="currency" class="form-select">
                    <option value="">--Select Currency--</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency }}" {{ request('currency') == $currency ? 'selected' : '' }}>
                            {{ $currency }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <label for="per_page" class="form-label">Invoices per page:</label>
            <select name="per_page" id="per_page" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2">Search</button>
        </div>
    </form>

    @if($invoices->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Tax</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</td>
                        <td>{{ number_format($invoice->amount, 2) }}  {{ $invoice->currency }}</td>
                        <td>{{ number_format($invoice->tax, 2) }}  {{ $invoice->currency }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>{{ $invoice->due_date }}</td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @if(auth()->user()->role === App\Models\User::STATUS_ADMIN)
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

        <div class="d-flex justify-content-center">
            {{ $invoices->appends(request()->all())->links('vendor/pagination/simple-default') }}
        </div>
    @else
        <p>No invoices found.</p>
    @endif
@endsection
