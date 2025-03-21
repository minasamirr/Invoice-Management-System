@extends('layouts.app')

@section('content')
    <h2>Advanced Invoice Search</h2>

    <!-- Search Form -->
    <form action="{{ route('invoices.search') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Invoice Number -->
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ request('invoice_number') }}">
            </div>
            <!-- Customer Name -->
            <div class="col-md-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ request('customer_name') }}">
            </div>
            <!-- Invoice Date Range -->
            <div class="col-md-3">
                <label for="invoice_date_from" class="form-label">Invoice Date From</label>
                <input type="date" name="invoice_date_from" id="invoice_date_from" class="form-control" value="{{ request('invoice_date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="invoice_date_to" class="form-label">Invoice Date To</label>
                <input type="date" name="invoice_date_to" id="invoice_date_to" class="form-control" value="{{ request('invoice_date_to') }}">
            </div>
            <!-- Invoice Amount Range -->
            <div class="col-md-3 mt-3">
                <label for="invoice_amount_from" class="form-label">Amount From</label>
                <input type="number" step="0.01" name="invoice_amount_from" id="invoice_amount_from" class="form-control" value="{{ request('invoice_amount_from') }}">
            </div>
            <div class="col-md-3 mt-3">
                <label for="invoice_amount_to" class="form-label">Amount To</label>
                <input type="number" step="0.01" name="invoice_amount_to" id="invoice_amount_to" class="form-control" value="{{ request('invoice_amount_to') }}">
            </div>
            <!-- Payment Status -->
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

    <!-- Display Search Results -->
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
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</td>
                        <td>${{ number_format($invoice->amount, 2) }}</td>
                        <td>${{ number_format($invoice->tax, 2) }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>{{ $invoice->due_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $invoices->appends(request()->all())->links('vendor/pagination/simple-default') }}
        </div>
    @else
        <p>No invoices found.</p>
    @endif
@endsection
