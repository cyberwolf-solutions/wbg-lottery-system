<!DOCTYPE html>
<html>
<head>
    <title>New {{ $requestType }} Request</title>
</head>
<body>
    <h1>New {{ $requestType }} Request</h1>
    <p>A new {{ strtolower($requestType) }} request has been submitted by {{ $userName }}.</p>

    <h2>Details:</h2>
    <ul>
        <li><strong>Amount:</strong> {{ $details['amount'] }}</li>
        @if($requestType === 'Deposit')
            <li><strong>Bank:</strong> {{ $details['bank'] ?? 'N/A' }}</li>
            <li><strong>Account Number:</strong> {{ $details['account_number'] ?? 'N/A' }}</li>
            <li><strong>Reference:</strong> {{ $details['reference'] ?? 'N/A' }}</li>
            <li><strong>Deposit Type:</strong> {{ $details['deposit_type'] ?? 'N/A' }}</li>
            @if(isset($details['image']))
                <li><strong>Attachment:</strong> <a href="{{ asset($details['image']) }}">View Attachment</a></li>
            @endif
        @else
            <li><strong>Withdrawal Type:</strong> {{ $details['withdrawal_type'] ?? 'N/A' }}</li>
            <li><strong>Wallet Address:</strong> {{ $details['wallet_address'] ?? 'N/A' }}</li>
        @endif
        <li><strong>Status:</strong> Pending</li>
        <li><strong>Date:</strong> {{ now()->toDateTimeString() }}</li>
    </ul>

    <p>Please review the request in the admin panel.</p>
</body>
</html>