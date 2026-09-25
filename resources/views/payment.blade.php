<!DOCTYPE html>
<html>
<head>
    <title>Create Vendor Payment</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
        }
        .container { 
            max-width: 600px; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
        }
        .form-group { 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        }
        input, select, textarea 
        { 
        width: 100%; 
        padding: 8px; 
        box-sizing: border-box; 
        }
        button { 
            background-color: #007bff; 
            color: white; 
            padding: 10px 15px; 
            border: none; cursor: pointer; 
        }
        button:hover { 
            background-color: #0056b3; 
        }
        .alert-success { 
            color: green; margin-bottom: 15px; 
        }
        .alert-danger { 
            color: red; margin-bottom: 15px; 
        }
    </style>
</head>
<body>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="margin: 0;">Create Vendor Payment</h2>
        <div style="display: flex; gap: 15px; align-items: center;">
            <a href="{{ route('approvals.index') }}" style="color: #007bff; text-decoration: none; font-weight: bold;">View Approvals</a>
            
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #dc3545; font-weight: bold; cursor: pointer; padding: 0; font-size: inherit;">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('payment.store') }}">
        @csrf

        <div class="form-group">
            <label>Payment No.</label>
            <input type="text" name="payment_no" value="{{ $nextPaymentNo }}" readonly>
        </div>

       
        <div class="form-group">
            <label>Vendor</label>
            <select name="vendor_id" required>
                <option value="">Select Active Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->vendor_type }})</option>
                @endforeach
            </select>
        </div>

     
        <div class="form-group">
            <label>Amount</label>
            <input type="number" name="amount" min="1" required>
        </div>

   
        <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"></textarea>
        </div>

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>