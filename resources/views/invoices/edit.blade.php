@extends('layouts.app')

@section('content')
    <h2>Edit Invoice</h2>
    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" id="customer_id" class="form-select" required>
                <option value="">Select a Customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }} ({{ $customer->email }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" step="0.01" class="form-control" value="{{ $invoice->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="tax" class="form-label">Tax</label>
            <input type="number" name="tax" id="tax" step="0.01" class="form-control" value="{{ $invoice->tax }}">
        </div>
        <div class="mb-3">
        <label for="currency" class="form-label">Currency</label>
            <select name="currency" id="currency" class="form-select" required>
                <option value="">Select Currency</option>
                @foreach(\App\Models\Invoice::currencies() as $currency)
                    <option value="{{ $currency }}" {{ $invoice->currency == $currency ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                @foreach($statuses as $status)
                <option value="{{ $status }}" {{ $invoice->status === $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $invoice->due_date }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $invoice->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Invoice</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
    <br>
@endsection
