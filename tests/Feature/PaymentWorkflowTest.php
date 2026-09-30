<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Workflow;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function requester_can_submit_payment_and_assign_workflow(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $workflow = Workflow::factory()->create();

        $response = $this->actingAs($user)->post(route('payments.store'), [
            'vendor_id' => $vendor->id,
            'workflow_id' => $workflow->id,
            'amount' => 15000,
            'payment_date' => '2026-09-29',
            'description' => 'Office Supplies Purchase',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('payments', [
            'amount' => 15000,
            'description' => 'Office Supplies Purchase',
        ]);
    }

    #[Test]
    public function wrong_role_cannot_approve_step_server_side(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $workflow = Workflow::factory()->create();
        
        $payment = Payment::factory()->create([
            'vendor_id' => $vendor->id,
            'workflow_id' => $workflow->id,
        ]);

        $response = $this->actingAs($user)->post(route('payments.approve', $payment->id), [
            'remarks' => 'Approving step',
        ]);

        $response->assertForbidden(); 
    }

    #[Test]
    public function rejection_requires_mandatory_remarks(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $workflow = Workflow::factory()->create();
        
        $payment = Payment::factory()->create([
            'vendor_id' => $vendor->id,
            'workflow_id' => $workflow->id,
        ]);

        $response = $this->actingAs($user)->post(route('payments.reject', $payment->id), [
            'remarks' => '', // Empty remarks to test validation failure
        ]);

        $response->assertSessionHasErrors('remarks');
    }
}