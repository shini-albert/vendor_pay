<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Vendor;
class PaymentController extends Controller
{
    public function create()
    {
        $vendors = Vendor::where('is_active', '1')->get();
        return view('payment', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'    => 'required|exists:vendors,id',
            'amount'       => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'description'  => 'nullable|string'
        ]);

       $workflowId =1;
        Payment::create([
            'payment_no'      => strtoupper(uniqid()),
            'vendor_id'       => $request->vendor_id,
            'amount'          => $request->amount,
            'payment_date'    => $request->payment_date,
            'description'     => $request->description,
            'workflow_id'     => $workflowId,
            'status'          => 'pending',
            'current_step_no' => 1, 
            'created_by'      => 1
        ]);

        return redirect()->route('payment')->with('success', 'Payment submitted successfully!');
    }


}
