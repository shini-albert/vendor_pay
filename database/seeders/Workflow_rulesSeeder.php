<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\workflow_rule;
class Workflow_rulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         workflow_rule::create([
            'workflow_id' => 1,
            'field' => 'amount',
            'operator' => '<=',
            'value' => '50000',
         ]);
         workflow_rule::create([
            'workflow_id' => 1,
            'field' => 'amount',
            'operator' => '>',
            'value' => '50000',
         ]);
          workflow_rule::create([
            'workflow_id' => 1,
            'field' => 'amount',
            'operator' => '>',
            'value' => '200000',
         ]);
    }
}
