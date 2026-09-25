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
        $Normal=workflow::create([
            'name' => 'Normal payment',
            'code' => 'NORMAL_PAY',
         ]);
         $High=workflow::create([
            'name' => 'High Value Payment',
            'code' => 'HIGH_PAY',
         ]);
         $VHigh=workflow::create([
            'name' => 'Very High Value Payment',
            'code' => 'VERY_HIGH_PAY',
         ]);
    }
}
