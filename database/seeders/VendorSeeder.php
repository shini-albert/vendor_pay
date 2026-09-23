<?php

namespace Database\Seeders;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Vendor::create([
            'name' => 'ABC Suppliers',
            'vendor_type' => 'General',
         ]);
         Vendor::create([
            'name' => 'XYZ Services	Service',
            'vendor_type' => 'Service',
         ]);
         Vendor::create([
            'name' => 'Kerala Stationery Mart',
            'vendor_type' => 'Supplies',
         ]);
         Vendor::create([
            'name' => 'National Computers',
            'vendor_type' => 'IT',
         ]);
        Vendor::create([
            'name' => 'Metro Office Solutions',
            'vendor_type' => 'General',
         ]);

    }
}
