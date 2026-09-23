<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Create Vendor Payment</h1>

<form method="POST" action="{{ route('payments.store') }}">
    @csrf
    <label>Payment No.:</label><br>
        <input type="text" name="payment_no" value="PAY-{{ strtoupper(substr(uniqid(), -6)) }}"><br><br>
    <label>Vendor:</label><br>
    <select name="vendor_id" required>
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->vendor_type }})</option>
        @endforeach
    </select><br><br>
    <label>Amount:</label><br>
    <input type="number" step="1" name="amount" required><br><br>
    <label>Payment Date:</label><br>
    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required><br><br>
    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>
    <button type="submit">Submit</button>
</form>
</body>
</html>