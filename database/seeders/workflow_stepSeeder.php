<?php

namespace Database\Seeders;

use App\Models\Workflow_Step;
use Illuminate\Database\Seeder;

class workflow_stepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Workflow_Step::create([
            'workflow_id' => 1,
            'step_no'     => 1,
            'role_id'     => 3, 
            'step_name'   => 'Supervisor Approval',
        ]);

        
        Workflow_Step::create([
            'workflow_id' => 2,
            'step_no'     => 1,
            'role_id'     => 3,
            'step_name'   => 'Supervisor Approval',
        ]);
        Workflow_Step::create([
            'workflow_id' => 2,
            'step_no'     => 2,
            'role_id'     => 2, 
            'step_name'   => 'Manager Approval',
        ]);

       
        Workflow_Step::create([
            'workflow_id' => 3,
            'step_no'     => 1,
            'role_id'     => 3, 
            'step_name'   => 'Supervisor Approval',
        ]);
        Workflow_Step::create([
            'workflow_id' => 3,
            'step_no'     => 2,
            'role_id'     => 2,
            'step_name'   => 'Manager Approval',
        ]);
        Workflow_Step::create([
            'workflow_id' => 3,
            'step_no'     => 3,
            'role_id'     => 1, 
            'step_name'   => 'Admin Approval',
        ]);
    }
}