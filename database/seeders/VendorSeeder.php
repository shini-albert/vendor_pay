<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::create(['id' => 1, 'name' => 'ABC Suppliers',           'vendor_type' => 'General']);
        Vendor::create(['id' => 2, 'name' => 'XYZ Services',            'vendor_type' => 'Service']);
        Vendor::create(['id' => 3, 'name' => 'Kerala Stationery Mart', 'vendor_type' => 'Supplies']);
        Vendor::create(['id' => 4, 'name' => 'National Computers',      'vendor_type' => 'IT']);
        Vendor::create(['id' => 5, 'name' => 'Metro Office Solutions',  'vendor_type' => 'General']);
    }
}