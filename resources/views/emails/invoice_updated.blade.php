<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Dear Customer,</p>
    <p>Your invoice (Invoice Number: {{ $invoice->invoice_number }}) has been updated.</p>

    <h3>Invoice Details:</h3>
    <ul>
        <li><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</li>
        <li><strong>Amount:</strong> {{ $invoice->amount }} {{ $invoice->currency }}</li>
        <li><strong>Tax:</strong> {{ $invoice->tax }} {{ $invoice->currency }}</li>
        <li><strong>Total Amount:</strong> {{ $invoice->total_amount }} {{ $invoice->currency }}</li>
        <li><strong>Description:</strong> {{ $invoice->description }}</li>
    </ul>

    <h3>Updated Fields:</h3>
    <ul>
        @foreach($changes as $field => $values)
            <li>
                <strong>{{ ucfirst($field) }}:</strong> {{ $values['old'] }} &rarr; {{ $values['new'] }}
            </li>
        @endforeach
    </ul>

    <p>If you have any questions, please contact our support team.</p>
    <p>Thank you!</p>
</body>
</html>
