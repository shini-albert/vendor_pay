<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vendor;
use App\Models\Workflow;
use App\Models\User;
/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Payment::class;
    public function definition(): array
    {
        return [
            'payment_no' => 'PAY-' . fake()->unique()->numerify('#####'),
            'vendor_id' => Vendor::factory(),
            'amount' => 30000,
            'payment_date' => now()->toDateString(),
            'description' => fake()->sentence(),
            'workflow_id' => Workflow::factory(),
            'status' => 'pending',
            'current_step_no' => 1,
            'created_by' => User::factory(),
        ];
    }
}
