<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Updated</title>
</head>
<body>
    <h1>Invoice Updated</h1>
    <p>Dear Customer,</p>
    <p>Your invoice (Invoice Number: {{ $invoice->invoice_number }}) has been updated.</p>
    <h3>Updated Details:</h3>
    <ul>
        @foreach($changes as $field => $values)
            <li>
                <strong>{{ ucfirst($field) }}:</strong>
                {{ $values['old'] }} -> {{ $values['new'] }}
            </li>
        @endforeach
    </ul>
    <p>If you have any questions, please contact our support team.</p>
    <p>Thank you!</p>
</body>
</html>
