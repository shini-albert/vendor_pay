<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Approvals</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 4px; }
        .btn-approve { background: #28a745; color: white; }
        .btn-reject { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <h2>Pending Payment Approvals</h2>

    @if(session('message'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Payment No</th>
                <th>Vendor</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Current Step No</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingPayments as $payment)
                <tr>
                    <td>{{ $payment->payment_no }}</td>
                    <td>{{ $payment->vendor->name ?? 'N/A' }}</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>Step {{ $payment->current_step_no }}</td>
                    <td>{{ $payment->creator->name ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('approvals.process', $payment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="text" name="remarks" placeholder="Optional remarks..." />
                            <button type="submit" name="action" value="approved" class="btn btn-approve">Approve</button>
                            <button type="submit" name="action" value="rejected" class="btn btn-reject">Reject</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No pending approvals for your role.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>