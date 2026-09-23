<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
     /*   Role::insert([['name'=>'Administrator','code'=>'ADMIN'],['name'=>'Requester','code'=>'REQUE'],
        ['name'=>'Manager','code'=>'MANGR'],['name'=>'ManageSuprevisor','code'=>'SUPER'],]);*/
        Role::create([
            'name' => 'Administrator',
            'code' => 'ADMIN',
        ]);
       

        Role::create([
            'name' => 'Manager',
            'code' => 'MANGR',
        ]);

        Role::create([
            'name' => 'Suprevisor',
            'code' => 'SUPER',
        ]);
         Role::create([
            'name' => 'Requester',
            'code' => 'REQUE',
        ]);
    }
}
