<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\workflow_rule;

class Workflow_rulesSeeder extends Seeder
{
    public function run(): void
    {
     
        workflow_rule::create([
            'id'          => 1,
            'workflow_id' => 1,
            'field'       => 'amount',
            'operator'    => '<=',
            'value'       => '50000',
        ]);

     
        workflow_rule::create([
            'id'          => 2,
            'workflow_id' => 1,
            'field'       => 'amount',
            'operator'    => '>', 
            'value'       => '50000',
        ]);

   
        workflow_rule::create([
            'id'          => 3,
            'workflow_id' => 1,
            'field'       => 'amount',
            'operator'    => '>',
            'value'       => '200000',
        ]);
    }
}