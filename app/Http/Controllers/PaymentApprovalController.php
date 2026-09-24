<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentApproval;
use App\Models\Workflow_Step;
use App\Models\workflow_rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentApprovalController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roleId = $user->role_id;

  
        $payments = Payment::with(['vendor', 'workflow', 'creator', 'approvals.user.role'])
            ->where('status', 'pending')
            ->get()
            ->filter(function ($payment) use ($roleId) {
                
                $rule = workflow_rule::where('workflow_id', $payment->workflow_id)->first();
                
                if (!$rule) {
                    return false;
                }

               
                $step = Workflow_Step::where('workflow_rule_id', $rule->id)
                    ->where('step_no', $payment->current_step_no)
                    ->first();

               
                return $step && $step->role_id == $roleId;
            });

        return view('approvals', compact('payments'));
    }


   
    public function approve(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $rule = workflow_rule::where('workflow_id', $payment->workflow_id)->first();

        if (!$rule) {
            return back()->with('error', 'No matching rule found.');
        }

        $step = Workflow_Step::where('workflow_rule_id', $rule->id)
            ->where('step_no', $payment->current_step_no)
            ->first();

        if (!$step) {
            return back()->with('error', 'Workflow step not found.');
        }

   
        PaymentApproval::create([
            'payment_id'      => $payment->id,
            'workflow_step_id'=> $step->id,
            'user_id'         => Auth::id(),
            'role_id'         => Auth::user()->role_id,
            'action'          => 'approved',
            'remarks'         => $request->remarks ?? 'Approved',
            'acted_at'        => now(),
        ]);

 
        $nextStep = Workflow_Step::where('workflow_rule_id', $rule->id)
            ->where('step_no', $payment->current_step_no + 1)
            ->first();

        if ($nextStep) {
            
            $payment->update([
                'current_step_no' => $payment->current_step_no + 1
            ]);
        } else {
            
            $payment->update([
                'status' => 'approved'
            ]);
        }

        return back()->with('success', 'Payment approved successfully!');
    }

   
    public function reject(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $rule = workflow_rule::where('workflow_id', $payment->workflow_id)->first();
        $step = $rule ? Workflow_Step::where('workflow_rule_id', $rule->id)->where('step_no', $payment->current_step_no)->first() : null;

        PaymentApproval::create([
            'payment_id'      => $payment->id,
            'workflow_step_id'=> $step ? $step->id : null,
            'user_id'         => Auth::id(),
            'role_id'         => Auth::user()->role_id,
            'action'          => 'rejected',
            'remarks'         => $request->remarks ?? 'Rejected',
            'acted_at'        => now(),
        ]);

        $payment->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Payment has been rejected.');
    }
}