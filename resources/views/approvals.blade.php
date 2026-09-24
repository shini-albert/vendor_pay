<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approvals</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 25px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .header-bar h2 { margin: 0; font-size: 20px; color: #2c3e50; }
        
        .table-container { background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; text-align: left; }
        th { background-color: #f8f9fa; color: #495057; padding: 12px 15px; border-bottom: 2px solid #dee2e6; font-weight: 600; white-space: nowrap; }
        td { padding: 12px 15px; border-bottom: 1px solid #e9ecef; vertical-align: top; }
        tr:hover { background-color: #f1f3f5; }

        .badge-status { background-color: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; }
        .badge-step { background-color: #e3f2fd; color: #0d47a1; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        
        textarea { width: 100%; padding: 6px 8px; border: 1px solid #ced4da; border-radius: 4px; font-family: inherit; font-size: 13px; box-sizing: border-box; resize: vertical; }
        
        .btn-group { display: flex; gap: 6px; margin-top: 6px; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; color: #fff; font-size: 12px; }
        .btn-approve { background-color: #28a745; }
        .btn-reject { background-color: #dc3545; }
        .btn-toggle { background-color: #6c757d; font-size: 11px; padding: 3px 8px; margin-top: 5px; border-radius: 3px; }

        .history-box { background: #f8f9fa; padding: 10px; border-radius: 4px; border: 1px dashed #ccc; font-size: 12px; margin-top: 5px; display: none; }
        .history-table { width: 100%; margin-top: 5px; border-collapse: collapse; }
        .history-table th, .history-table td { border: 1px solid #dee2e6; padding: 4px 8px; }

        .empty-box { text-align: center; padding: 40px; background: #fff; border-radius: 8px; color: #777; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bar">
        <h2>Payment Approvals List</h2>
        <div>
            <a href="{{ route('payment') }}" style="color: #007bff; text-decoration: none; font-weight: bold; margin-left: 10px;">+ New Payment</a>
        </div>
    </div>

    @if(count($payments) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Payment No.</th>
                        <th>Date</th>
                        <th>Vendor</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Workflow & Step</th>
                        <th>Status</th>
                        <th>Approval History</th>
                        <th style="min-width: 220px;">Remarks & Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>
                                <strong>{{ $loop->iteration }}</strong>
                            </td>
                            <td>
                                <span>{{ $payment->payment_no }}</span><br>
                            </td>
                            <td>
                                {{ $payment->created_at->format('d M Y') }}    
                            </td>


                            <td>
                                <strong>{{ $payment->vendor->name ?? 'Vendor ID: ' . $payment->vendor_id }}</strong>
                            </td>


                            <td>
                                <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                            </td>

                           
                            <td style="max-width: 180px;">
                                {{ $payment->description ?? 'N/A' }}
                            </td>

                  
                            <td>
                                {{ $payment->workflow->name ?? 'Workflow #' . $payment->workflow_id }}<br>
                                <span class="badge-step">Step {{ $payment->current_step_no }}</span>
                            </td>

             
                            <td>
                                <span class="badge-status">{{ strtoupper($payment->status ?? 'pending') }}</span>
                            </td>

                            <td>
                                <button type="button" class="btn btn-toggle" onclick="toggleHistory({{ $payment->id }})">View History</button>
                                <div id="history-{{ $payment->id }}" class="history-box">
                                    <strong>Audit Logs:</strong>
                                    <p style="margin: 3px 0; color: #6c757d; font-style: italic;">No previous approvals logged yet.</p>
                                </div>
                            </td>


                            <td>
                                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Action simulated! Implement PaymentApprovalController to persist actions.');">
                                    @csrf
                                    <textarea id="remarks_{{ $payment->id }}" name="remarks" rows="2" placeholder="Enter remarks..."></textarea>
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-approve">Approve</button>
                                        <button type="submit" class="btn btn-reject">Reject</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-box">
            <h3>No Payments Found</h3>
            <p>Go to the <a href="{{ route('payment') }}">Payment Page</a> and create a payment to see it appear here.</p>
        </div>
    @endif
</div>

<script>
    function toggleHistory(id) {
        var box = document.getElementById('history-' + id);
        if (box.style.display === 'none' || box.style.display === '') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
</script>

</body>
</html>