<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Vendor::class;
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'vendor_type' => 'General',
            'is_active' => '1',
        ];
    }
}
