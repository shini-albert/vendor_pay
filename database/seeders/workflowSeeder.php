<?php

namespace Database\Seeders;
use App\Models\workflow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class workflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        workflow::create([
            'name' => 'Payment Approval',
            'code' => 'PAY_APPROVAL',
         ]);
         workflow::create([
            'name' => 'Receipt Approval',
            'code' => 'REC_APPROVAL',
         ]);
    }
}
