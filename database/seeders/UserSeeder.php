<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        User::create([
            'name' => 'Rehna',
            'email' => 'rehna@gmail.com',
            'password' => '123456',
            'role_id' => 4,  ]);
         User::create([
            'name' => 'Seniya',
            'email' => 'seniya@gmail.com',
            'password' => '123456',
            'role_id' => 4,  ]);
        User::create([
            'name' => 'Seniya Najeem',
            'email' => 'seniyanajeem@gmail.com',
            'password' => '123456',
            'role_id' => 3,  ]);
        User::create([
            'name' => 'Rehna MN',
            'email' => 'rehnamn@gmail.com',
            'password' => '123456',
            'role_id' => 3,  ]);
         User::create([
            'name' => 'Shini',
            'email' => 'shini@gmail.com',
            'password' => '123456',
            'role_id' => 2,  ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456',
            'role_id' => 1,  ]);
    }
}
