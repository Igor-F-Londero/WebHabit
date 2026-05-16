<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin HabitFlow',
            'email'    => 'admin@habitflow.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
            'active'   => true,
        ]);

        User::create([
            'name'     => 'Igor Londero',
            'email'    => 'igor@habitflow.com',
            'password' => bcrypt('password'),
            'role'     => 'user',
            'active'   => true,
        ]);
    }
}
