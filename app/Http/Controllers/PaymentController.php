<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\workflow_rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function create()
    {
        // Restrict access: Only users with the 'Requester' role can view the payment creation page
        if (Auth::user()->role_id !== 4) { // Or Auth::user()->role_id !== 4 based on your roles table
            abort(403, 'Unauthorized action. Only Requesters can create payments.');
        }

        $vendors = Vendor::where('is_active', '1')->get();
        $lastPayment = Payment::latest('id')->first();
        $nextId = $lastPayment ? $lastPayment->id + 1 : 1;
        $nextPaymentNo = str_pad($nextId, 4, '0', STR_PAD_LEFT);
        return view('payment', compact('vendors', 'nextPaymentNo'));
    }

    public function store(Request $request)
    {
        // Restrict access: Only users with the 'Requester' role can submit payment requests
        if (Auth::user()->role_id !== 4) { // Or Auth::user()->role_id !== 4
            abort(403, 'Unauthorized action. Only Requesters can create payments.');
        }

        $request->validate([
            'payment_no'   => 'required|string|unique:payments,payment_no',
            'vendor_id'    => ['required','integer', Rule::exists('vendors', 'id')->where('is_active', '1'),],
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'description'  => 'required|string',
        ], [
            'vendor_id.exists' => 'The selected vendor is currently inactive.',
            'amount.min'       => 'The payment amount must be a positive number greater than zero.',
            'description.required' => 'Description for the payment is mandatory.',
        ]);

        $workflowId = $this->checkWorkflowId($request->amount);

        Payment::create([
            'payment_no'      => $request->payment_no,
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

    private function checkWorkflowId($amount)
    {
        $rules = workflow_rule::orderBy('value', 'asc')->get();

        foreach ($rules as $rule) {
            $matched = match ($rule->operator) {
                '<'  => $amount < $rule->value,
                '<=' => $amount <= $rule->value,
                '='  => $amount == $rule->value,
                '>'  => $amount > $rule->value,
                '>=' => $amount >= $rule->value,
                default => false,
            };

            if ($matched) {
                return $rule->workflow_id;
            }
        }
        return 1;
    }
}