<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Workflow;
use App\Models\Payment;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VendorSeeder;
use Database\Seeders\Workflow_rulesSeeder;
use Database\Seeders\workflow_stepSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(VendorSeeder::class);

        Workflow::factory()->create(['id' => 1, 'name' => 'Low Value Workflow', 'code' => 'WF-1']);
        Workflow::factory()->create(['id' => 2, 'name' => 'Medium Value Workflow', 'code' => 'WF-2']);
        Workflow::factory()->create(['id' => 3, 'name' => 'High Value Workflow', 'code' => 'WF-3']);

        $this->seed(Workflow_rulesSeeder::class);
        $this->seed(workflow_stepSeeder::class);
    }

    #[Test]
    public function required_users_can_log_in(): void
    {
        $response = $this->post('/login', [
            'username' => 'supervisor1',
            'password' => '123456',
        ]);

        $this->assertAuthenticated();
    }

    #[Test]
    public function supervisor_approval_workflow(): void
    {
        $requester = User::where('role_id', 4)->first();
        $supervisor = User::where('role_id', 3)->first();

        $response = $this->actingAs($requester)->post(route('payment.store'), [
            'payment_no' => 'PAY-30000',
            'vendor_id' => 1,
            'workflow_id' => 1,
            'amount' => 30000,
            'payment_date' => now()->toDateString(),
            'description' => 'Low value request',
        ]);

        $response->assertStatus(302);
        $payment = Payment::latest()->first();
        $this->assertEquals(1, $payment->current_step_no);

        $this->actingAs($supervisor)->post(route('payments.approve', $payment->id), [
            'remarks' => 'Approved by supervisor',
        ]);

        $payment->refresh();
        $this->assertEquals('approved', $payment->status);
        $this->assertDatabaseHas('payment_approvals', [
            'payment_id' => $payment->id,
            'action' => 'approved',
            'role_id' => 3,
        ]);
    }

    #[Test]
    public function supervisor_and_manager_approval_workflow(): void
    {
        $requester = User::where('role_id', 4)->first();
        $supervisor = User::where('role_id', 3)->first();
        $manager = User::where('role_id', 2)->first();

        $this->actingAs($requester)->post(route('payment.store'), [
            'payment_no' => 'PAY-75000',
            'vendor_id' => 1,
            'workflow_id' => 2,
            'amount' => 75000,
            'payment_date' => now()->toDateString(),
            'description' => 'Medium value equipment',
        ]);

        $payment = Payment::latest()->first();
        $this->actingAs($supervisor)->post(route('payments.approve', $payment->id), [
            'remarks' => 'First step approved',
        ]);

        $payment->refresh();
        $this->assertEquals(2, $payment->current_step_no);
        $this->assertEquals('pending', $payment->status);
        $this->actingAs($manager)->post(route('payments.approve', $payment->id), [
            'remarks' => 'Final step approved by manager',
        ]);

        $payment->refresh();
        $this->assertEquals('approved', $payment->status);
        $this->assertDatabaseCount('payment_approvals', 2);
    }

    #[Test]
    public function supervisor_manager_and_admin_approval_workflow(): void
    {
        $requester = User::where('role_id', 4)->first();
        $supervisor = User::where('role_id', 3)->first();
        $manager = User::where('role_id', 2)->first();
        $admin = User::where('role_id', 1)->first();
        $this->actingAs($requester)->post(route('payment.store'), [
            'payment_no' => 'PAY-250000',
            'vendor_id' => 1,
            'workflow_id' => 3,
            'amount' => 250000,
            'payment_date' => now()->toDateString(),
            'description' => 'High value asset purchase',
        ]);

        $payment = Payment::latest()->first();
        $this->actingAs($supervisor)->post(route('payments.approve', $payment->id), ['remarks' => 'OK']);
        $payment->refresh();
        $this->assertEquals(2, $payment->current_step_no);

        $this->actingAs($manager)->post(route('payments.approve', $payment->id), ['remarks' => 'OK']);
        $payment->refresh();
        $this->assertEquals(3, $payment->current_step_no);

        $this->actingAs($admin)->post(route('payments.approve', $payment->id), ['remarks' => 'Final signoff']);
        $payment->refresh();
        $this->assertEquals('approved', $payment->status);
    }

    #[Test]
    public function wrong_role_cannot_approve(): void
    {
        $requester = User::where('role_id', 4)->first();
        $payment = Payment::factory()->create(['amount' => 75000, 'workflow_id' => 2]);

        $response = $this->actingAs($requester)->post(route('payments.approve', $payment->id), [
            'remarks' => 'Unauthorized attempt',
        ]);

        $response->assertStatus(302); 
        $payment->refresh();
        $this->assertEquals(1, $payment->current_step_no);
    }

    #[Test]
    public function rejection_requires_mandatory_remarks(): void
    {
        $supervisor = User::where('role_id', 3)->first();
        $payment = Payment::factory()->create(['workflow_id' => 2]);

        $response = $this->actingAs($supervisor)->post(route('payments.reject', $payment->id), [
            'remarks' => '',
        ]);

        $response->assertSessionHasErrors('remarks');
    }

    #[Test]
    public function rejection_with_remarks_updates_status_to_rejected(): void
    {
        $supervisor = User::where('role_id', 3)->first();
        $payment = Payment::factory()->create(['workflow_id' => 2]);

        $response = $this->actingAs($supervisor)->post(route('payments.reject', $payment->id), [
            'remarks' => 'Budget allocation exceeded for this category.',
        ]);

        $response->assertStatus(302);
        $payment->refresh();
        $this->assertEquals('rejected', $payment->status);

        $this->assertDatabaseHas('payment_approvals', [
            'payment_id' => $payment->id,
            'action' => 'rejected',
            'remarks' => 'Budget allocation exceeded for this category.',
        ]);
    }
}