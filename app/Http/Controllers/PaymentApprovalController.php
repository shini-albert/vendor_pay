<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Workflow_Step;
use App\Models\PaymentApproval;
use Illuminate\Support\Facades\Auth;
use App\Models\workflow_rule;

class PaymentApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }


        $roleCode = strtolower($user->role->code ?? $user->role->name ?? '');
        if ($roleCode === 'requester') {
            return view('approvals', ['payments' => collect([])]);
        }

        $roleId = $user->role_id;

        $payments = Payment::with(['vendor', 'workflow', 'approvals.user'])
            ->where('status', 'pending')
            ->get()
            ->filter(function ($payment) use ($roleId) {
                $rule = $this->checkrule($payment->id);
                $step = Workflow_Step::where('workflow_rule_id', $rule)
                    ->where('step_no', $payment->current_step_no)
                    ->first();

                return $step && $step->role_id == $roleId;
            });

        return view('approvals', compact('payments'));
    }

    public function approve(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $user = Auth::user();

        // 1. Fetch current active step
        $step = Workflow_Step::where('workflow_rule_id',  $this->checkrule($payment->id))
            ->where('step_no', $payment->current_step_no)
            ->first();

        if (!$step || (int)$step->role_id !== (int)$user->role_id) {
            return back()->with('error', 'Unauthorized action for your role at this step.');
        }

        PaymentApproval::create([
            'payment_id'       => $payment->id,
            'workflow_step_id' => $step->id,
            'user_id'          => $user->id,
            'role_id'          => $user->role_id,
            'action'           => 'approved',
            'remarks'          => $request->remarks ?? 'Approved',
            'acted_at'         => now(),
        ]);


        $nextStep = Workflow_Step::where('workflow_rule_id', $this->checkrule($payment->id))
            ->where('step_no', $payment->current_step_no + 1)
            ->first();

        if ($nextStep) {

            $payment->update([
                'current_step_no' => $payment->current_step_no + 1,
                'status'          => 'pending',
            ]);
        } else {

            $payment->update([
                'status' => 'approved',
            ]);
        }
 
        return back()->with('success', "Payment approved successfully!");
    }
    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|min:3'
        ], [
            'remarks.required' => 'A remark/reason is required when rejecting a payment.'
        ]);

        $payment = Payment::findOrFail($id);
        $user = Auth::user();

        $step = Workflow_Step::where('workflow_rule_id', $this->checkrule($payment->id))
            ->where('step_no', $payment->current_step_no)
            ->first();

        if (!$step || $step->role_id != $user->role_id) {
            return back()->with('error', 'Unauthorized action for your role.');
        }

        PaymentApproval::create([
            'payment_id'       => $payment->id,
            'workflow_step_id' => $step->id,
            'user_id'          => $user->id,
            'role_id'          => $user->role_id,
            'action'           => 'rejected',
            'remarks'          => $request->remarks,
            'acted_at'         => now(),
        ]);

        $payment->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Payment has been rejected.');
    }

    public function checkrule($id)
    {
        $payid = Payment::find($id);
        //$payid->{$field}
        $rules = Workflow_rule::where('workflow_id', $payid->workflow_id)->orderBy('value', 'asc')->get();
        foreach ($rules as $rule) {
            $field = $rule->field;
            if ($rule->operator === '<=' && $payid->{$field} <= $rule->value) {
                return $rule->id;
            }
            else
            if ($rule->operator === '>' && $payid->{$field} > $rule->value) {
                return $rule->id;
            }
        }
        
        return 1; 
    }
}