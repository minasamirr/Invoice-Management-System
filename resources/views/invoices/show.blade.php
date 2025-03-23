@extends('layouts.app')

@section('content')
    <h2>Invoice Details</h2>
    <div class="card">
        <div class="card-header">
        <strong>Invoice Number:</strong> {{ $invoice->invoice_number }}
        </div>
        <div class="card-body">
        <p><strong>Customer:</strong>
            @if($invoice->customer)
            {{ $invoice->customer->name }} ({{ $invoice->customer->email }})
            @else
            N/A
            @endif
        </p>
        <p><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</p>
        <p><strong>Tax:</strong> ${{ number_format($invoice->tax, 2) }}</p>
        <p><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
        <p><strong>Status:</strong> {{ $invoice->status }}</p>
        <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
        <p><strong>Description:</strong> {{ $invoice->description ?? 'N/A' }}</p>
        </div>
        <div class="card-footer">
        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Back to Invoices</a>
        </div>
    </div>
@endsection
