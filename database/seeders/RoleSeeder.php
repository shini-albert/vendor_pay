<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['id' => 1, 'name' => 'Administrator', 'code' => 'admin']);
        Role::create(['id' => 2, 'name' => 'Manager',       'code' => 'manager']);
        Role::create(['id' => 3, 'name' => 'Supervisor',    'code' => 'supervisor']);
        Role::create(['id' => 4, 'name' => 'Requester',     'code' => 'requester']);
    }
}