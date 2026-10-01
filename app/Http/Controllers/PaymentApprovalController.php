<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Workflow_Step;
use App\Models\PaymentApproval;
use Illuminate\Support\Facades\Auth;

class PaymentApprovalController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roleCode = strtolower($user->role->code ?? $user->role->name ?? '');
        if ($roleCode === 'requester' || $user->role_id == 4) {
            return redirect()->route('payment')
                ->with('error', 'Unauthorized action. Requesters are not allowed to view the approvals page.');
        }

        $roleId = $user->role_id;

        $payments = Payment::with(['vendor', 'workflow', 'approvals.user'])
            ->where('status', 'pending')
            ->get()
            ->filter(function ($payment) use ($roleId) {
                $step = Workflow_Step::where('workflow_id', $payment->workflow_id)
                    ->where('step_no', $payment->current_step_no)
                    ->first();

                return $step && $step->role_id === $roleId;
            });

        return view('approvals', compact('payments'));
    }

    protected function authorizeCurrentStep(Payment $payment, $user)
    {
        if (!$user || !$user->role_id) {
            return null;
        }

       return Workflow_Step::where('workflow_id', $payment->workflow_id)
            ->where('step_no', $payment->current_step_no)
            ->where('role_id', $user->role_id)
            ->first();
    }
    public function approve(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->status !== 'pending') {
        return back()->with('error', 'This payment is already in a terminal state and cannot be modified.');
    }
        $user = Auth::user();

        $step = $this->authorizeCurrentStep($payment, $user);
        if (!$step) {
            return back()->with('error', 'Unauthorized action for your role at this step.');
        }
        $existingApproval = PaymentApproval::where('payment_id', $payment->id)
            ->where('workflow_step_id', $step->id)
            ->exists();

        if ($existingApproval) {
            return back()->with('error', 'This step has already been processed.');
        }


        PaymentApproval::create([
            'payment_id'       => $payment->id,
            'workflow_step_id' => $step->id,
            'step_no'          => $step->step_no,
            'user_id'          => $user->id,
            'role_id'          => $user->role_id,
            'action'           => 'approved',
            'remarks'          => $request->remarks ?? 'Approved',
            'acted_at'         => now(),
        ]);

        $nextStep = Workflow_Step::where('workflow_id', $payment->workflow_id)
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

        return back()->with('success', 'Payment approved successfully!');
    }

   
    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|min:3'
        ], [
            'remarks.required' => 'A remark/reason is required when rejecting a payment.'
        ]);

        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment is already in a terminal state and cannot be modified.');
        }

        $user = Auth::user();
        $step = $this->authorizeCurrentStep($payment, $user);

        if (!$step) {
            return back()->with('error', 'Unauthorized action for your role at this step.');
        }

        PaymentApproval::create([
            'payment_id'       => $payment->id,
            'workflow_step_id' => $step->id,
            'step_no'          => $step->step_no,
            'user_id'          => $user->id,
            'role_id'          => $user->role_id,
            'action'           => 'rejected',
            'remarks'          => $request->remarks,
            'acted_at'         => now(),
        ]);

        $payment->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Payment has been rejected.');
    }
}