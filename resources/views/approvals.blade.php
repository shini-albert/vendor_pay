<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approvals</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 25px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .header-bar h2 { margin: 0; font-size: 20px; color: #2c3e50; }

        .table-container { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; }
        th { background-color: #f8f9fa; color: #495057; font-weight: 600; padding: 12px 15px; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px 15px; border-bottom: 1px solid #e9ecef; vertical-align: top; }
        tr:hover { background-color: #f1f3f5; }

        .badge-status { background-color: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; }
        .badge-step { background-color: #e3f2fd; color: #0d47a1; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }

        textarea { width: 100%; padding: 6px 8px; border: 1px solid #ced4da; border-radius: 4px; font-family: inherit; font-size: 13px; box-sizing: border-box; }

        .btn-group { display: flex; gap: 6px; margin-top: 6px; }
        .btn { padding: 6px 12px; font-weight: bold; cursor: pointer; color: #fff; font-size: 12px; border: none; border-radius: 4px; }
        .btn-approve { background-color: #28a745; }
        .btn-reject { background-color: #dc3545; }
        .btn-toggle { background-color: #6c757d; font-size: 11px; padding: 3px 8px; margin-top: 5px; border-radius: 3px; }

        .history-box { background: #f8f9fa; padding: 10px; border-radius: 4px; border: 1px dashed #ccc; font-size: 12px; margin-top: 5px; display: none; }
        .empty-box { text-align: center; padding: 40px; background: #fff; border-radius: 8px; color: #777; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb; font-weight: 500; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-weight: 500; }
        .alert-danger ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bar">
        <h2>Payment Approvals List ({{ auth()->user()->role->name ?? 'User' }})</h2>
        <div>
            {{-- Always show the button regardless of user role --}}
            <a href="{{ route('payment') }}" style="color: #007bff; text-decoration: none; font-weight: bold; margin-left: 10px;">+ New Payment</a>

            <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 15px;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #dc3545; font-weight: bold; cursor: pointer; padding: 0; font-size: inherit;">
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Global Alert Banner Messages --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(count($payments) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Sl no.</th>
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
                        <td><strong>{{ $loop->iteration }}</strong></td>
                        <td><span>{{ $payment->payment_no }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
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
                            <div id="history-{{ $payment->id }}" class="history-box" style="display: none;">
                                <strong>Audit Logs:</strong>
                                @forelse($payment->approvals ?? [] as $history)
                                    <p style="margin: 3px 0; font-size: 11px;">
                                        <span class="badge bg-secondary" style="font-size: 9px;">
                                            Step {{ $history->step_no ?? $history->workflowStep->step_no ?? 'N/A' }}
                                        </span>
                                        <strong>{{ $history->user->name ?? 'User' }} ({{ $history->role->name ?? 'Role' }}):</strong>
                                        
                                        <span style="color: {{ $history->action === 'approved' ? '#198754' : '#dc3545' }}; font-weight: bold;">
                                            {{ ucfirst($history->action) }}
                                        </span>
                                        @if($history->remarks) ("{{ $history->remarks }}") @endif
                                        
                                        <span style="color: #6c757d; font-size: 10px;">
                                            ({{ \Carbon\Carbon::parse($history->acted_at)->format('Y-m-d H:i:s') }} - {{ \Carbon\Carbon::parse($history->acted_at)->diffForHumans() }})
                                        </span>
                                    </p>
                                @empty
                                    <p style="margin: 3px 0; color: #6c757d; font-style: italic;">No previous approvals logged yet.</p>
                                @endforelse
                            </div>
                        </td>
            
                        <td>
                        @if(isset($payment->can_approve) ? $payment->can_approve : true)
                            <textarea id="remarks-{{ $payment->id }}" name="remarks" rows="2" placeholder="Enter remarks..." style="margin-bottom: 6px;"></textarea>
                            <div class="btn-group">
                                <form action="{{ route('payments.approve', $payment->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="remarks" id="hidden-approve-remarks-{{ $payment->id }}">
                                    <button type="submit" class="btn btn-approve" onclick="document.getElementById('hidden-approve-remarks-{{ $payment->id }}').value = document.getElementById('remarks-{{ $payment->id }}').value;">Approve</button>
                                </form>
                                <form action="{{ route('payments.reject', $payment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to reject this payment?');">
                                    @csrf
                                    <input type="hidden" name="remarks" id="hidden-reject-remarks-{{ $payment->id }}">
                                    <button type="submit" class="btn btn-reject" onclick="document.getElementById('hidden-reject-remarks-{{ $payment->id }}').value = document.getElementById('remarks-{{ $payment->id }}').value;">Reject</button>
                                </form>
                            </div>
                        @else
                            <span style="color: #6c757d; font-style: italic; font-size: 12px;">No pending action</span>
                        @endif
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-box">
            <h3>No Payments Found</h3>
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