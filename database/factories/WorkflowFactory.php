<?php

namespace Database\Factories;

use App\Models\Workflow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workflow>
 */
class WorkflowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Workflow::class;
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Workflow',
            'code' => fake()->unique()->slug(),
            'is_active' => true,
        ];
    }
}
