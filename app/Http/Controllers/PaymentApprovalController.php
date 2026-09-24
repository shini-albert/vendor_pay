<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\PaymentApproval;
use App\Models\workflow_rule;
use App\Models\Workflow_step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentApprovalController extends Controller
{
    public function approve(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

       
        $rule = $this->findRule($payment);

        if (!$rule) {
            return back()->with('error', 'No matching rule found.');
        }

        $step = Workflow_step::where('workflow_id', $rule->workflow_id)
            ->where('step_no', $payment->current_step_no)
            ->first();

        if (!$step) {
            return back()->with('error', 'Workflow step not found.');
        }


       
        $user = Auth::user();

        if($step->role_id != $user->role_id) {
            return back()->with('error', 'User are not authorized to approve this payment.');
        }


        
        PaymentApproval::create([
            'payment_id' => $payment->id,
            'workflow_step_id' => $step->id,
            'user_id' => $user->id,
            'action' => 'approved',
            'acted_at' => now(),
        ]);

        $nextStep = Workflow_step::where('workflow_id', $rule->workflow_id)
            ->where('step_no', $step->step_no + 1)
            ->first();


        if ($nextStep) {

            // Move payment to next approval step
            $payment->current_step_no = $nextStep->step_no;
            $payment->save();

            return back()->with(
                'success','Payment approved and moved to next approval level.'
            );
        }


        $payment->status = 'approved';
        $payment->current_step_no = $step->step_no;
        $payment->save();

        return back()->with(
            'success',
            'Payment fully approved.'
        );
    }    

    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:250',
        ]);

        $payment = Payment::findOrFail($id);

        $rule = $this->findRule($payment);

        if (!$rule) {
            return back()->with('error', 'No matching rule found.');
        }

        $step = workflow_step::where('workflow_rule_id', $rule->id)
            ->where('step_no', $payment->current_step_no ?? 1)
            ->first();

        if (!$step) {
            return back()->with('error', 'Workflow step not found.');
        }

        $user = Auth::user();

        if (!$user || $step->role_id != $user->role_id) {
            return back()->with('error', 'You are not authorized to reject this payment.');
        }

        PaymentApproval::create([
            'payment_id'       => $payment->id,
            'workflow_step_id' => $step->id,
            'user_id'          => $user->id,
            'action'           => 'rejected',
            'description'      => $request->input('description'),
            'acted_at'         => now(),
        ]);

       
        $payment->status = 'rejected';
        $payment->save();

        return back()->with('success', 'Payment has been rejected successfully.');
    }

    private function findRule(Payment $payment)
    {
        $rules = workflow_rule::where(
            'workflow_id',
            $payment->workflow_id
        )->get();

        foreach ($rules as $rule) {

            $fieldValue = $payment->{$rule->field};

            $blnmatch = false;

            switch ($rule->operator) {

                case '=':
                    $blnmatch = ($fieldValue == $rule->value);
                    break;

                case '>':
                    $blnmatch = ($fieldValue > $rule->value);
                    break;

                case '>=':
                    $blnmatch = ($fieldValue >= $rule->value);
                    break;

                case '<':
                    $blnmatch = ($fieldValue < $rule->value);
                    break;

                case '<=':
                    $blnmatch = ($fieldValue <= $rule->value);
                    break;

                case '!=':
                    $blnmatch = ($fieldValue != $rule->value);
                    break;
            }

            if ($blnmatch) {
                return $rule;
            }
        }
    }
}
