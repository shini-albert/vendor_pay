<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Workflow_rule;
use Illuminate\Support\Facades\Auth;
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

       $workflowId =$this->checkWorkflow($request->amount);;
        Payment::create([
            'payment_no'      => strtoupper(uniqid()),
            'vendor_id'       => $request->vendor_id,
            'amount'          => $request->amount,
            'payment_date'    => $request->payment_date,
            'description'     => $request->description,
            'workflow_id'     => $workflowId,
            'status'          => 'pending',
            'current_step_no' => 1, 
            'created_by'      => Auth::user()->id
        ]);

        return redirect()->route('payment')->with('success', 'Payment submitted successfully!');
    }
    
    public function checkWorkflow($amount)
    {
        $rules = workflow_rule::orderByDesc('value')->get();
        
        foreach ($rules as $rule) {
            if ($rule->operator === '>' && $amount > $rule->value) {
                return $rule->workflow_id;
            }
            if ($rule->operator === '<=' && $amount <= $rule->value) {
                return $rule->workflow_id;
            }
        }
        
        return 1; 
    }

}
