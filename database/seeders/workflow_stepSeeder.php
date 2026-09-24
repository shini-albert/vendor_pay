<?php

namespace Database\Seeders;

use App\Models\Workflow_Step;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class workflow_stepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Normal
        Workflow_Step::create( [
                'workflow_rule_id' => 1,
                'step_no' => 1,
                'role_id' => 3,
                'step_name' => 'Supervisor Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],);
             // High Value 
        Workflow_Step::create(  [
                'workflow_rule_id' => 2,
                'step_no' => 1,
                'role_id' => 3,
                'step_name' => 'Supervisor Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],);
        Workflow_Step::create( [
                'workflow_rule_id' => 2,
                'step_no' => 2,
                'role_id' => 2,
                'step_name' => 'Manager Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],);
             // 2lakh 
        Workflow_Step::create(  [
                'workflow_rule_id' => 3,
                'step_no' => 1,
                'role_id' => 3,
                'step_name' => 'Supervisor Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],);
        Workflow_Step::create( [
                'workflow_rule_id' => 3,
                'step_no' => 2,
                'role_id' => 2,
                'step_name' => 'Manager Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],);
        Workflow_Step::create( [
                'workflow_rule_id' => 3,
                'step_no' => 3,
                'role_id' => 1,
                'step_name' => 'Admin Approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],);
    }
}
