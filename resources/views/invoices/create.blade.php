@extends('layouts.app')

@section('content')
    <h2>Create Invoice</h2>
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <div class="mb-3">
        <label for="customer_id" class="form-label">Customer</label>
        <select name="customer_id" id="customer_id" class="form-select" required>
            <option value="">Select a Customer</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
            @endforeach
        </select>
        </div>
        <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
        <label for="tax" class="form-label">Tax</label>
        <input type="number" name="tax" id="tax" step="0.01" class="form-control">
        </div>
        <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            @foreach($statuses as $status)
            <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
        </div>
        <div class="mb-3">
        <label for="due_date" class="form-label">Due Date</label>
        <input type="date" name="due_date" id="due_date" class="form-control" required>
        </div>
        <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Create Invoice</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
