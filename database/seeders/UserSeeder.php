<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');

        User::create(['name' => 'Requester One', 'username' => 'requester1',  'email' => 'requester1@gmail.com', 'password' => $password, 'role_id' => 4]);
        User::create(['name' => 'Seniya',         'email' => 'seniya@gmail.com',     'password' => $password, 'role_id' => 4]);

        User::create(['name' => 'Supervisor One','username' => 'supervisor1', 'email' => 'supervisor1@gmail.com','password' => $password, 'role_id' => 3]);
        User::create(['name' => 'Rehna MN',       'email' => 'rehnamn@gmail.com',    'password' => $password, 'role_id' => 3]);

        User::create(['name' => 'Manager One', 'username' => 'manager1',   'email' => 'manager1@gmail.com',   'password' => $password, 'role_id' => 2]);

        User::create(['name' => 'Administrator', 'username' => 'admin', 'email' => 'admin@gmail.com',      'password' => $password, 'role_id' => 1]);
    }
}