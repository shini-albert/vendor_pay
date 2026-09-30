<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workflow::create(['id' => 1, 'name' => 'Normal Payment Approval',    'code' => 'normal',          'is_active' => '1']);
        Workflow::create(['id' => 2, 'name' => 'High Value Approval',        'code' => 'high_value',      'is_active' => '1']);
        Workflow::create(['id' => 3, 'name' => 'Very High Value Approval',   'code' => 'very_high_value', 'is_active' => '1']);
    }
}