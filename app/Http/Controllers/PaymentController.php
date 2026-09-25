<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\workflow_rule;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create()
    {
        $vendors = Vendor::where('is_active', '1')->get();
        $lastPayment = Payment::latest('id')->first();
        $nextId = $lastPayment ? $lastPayment->id + 1 : 1;
        $nextPaymentNo = str_pad($nextId, 4, '0', STR_PAD_LEFT);
        return view('payment', compact('vendors', 'nextPaymentNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_no'   => 'required|string|unique:payments,payment_no',
            'vendor_id'    => 'required|exists:vendors,id',
            'amount'       => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'description'  => 'nullable|string'
        ]);

        //$workflowId = 1;

        
        Payment::create([
            'payment_no'      => $request->payment_no,
            'vendor_id'       => $request->vendor_id,
            'amount'          => $request->amount,
            'payment_date'    => $request->payment_date,
            'description'     => $request->description,
            'workflow_id'     => 1,
            'status'          => 'pending',
            'current_step_no' => 1, 
            'created_by'      => Auth::user()->id
        ]);

        return redirect()->route('payment')->with('success', 'Payment submitted successfully!');
    }
    
    //public function checkWorkflow($amount)
    /*{
        $rules = workflow_rule::orderBy('value')->get();
        
        foreach ($rules as $rule) {
            if ($rule->operator === '>' && $amount > $rule->value) {
                return $rule->id;
            }
            if ($rule->operator === '<=' && $amount <= $rule->value) {
                return $rule->id;
            }
        }
        
        return 1; /
    }*/
}