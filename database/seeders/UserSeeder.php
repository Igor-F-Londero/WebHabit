<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@webhabit.com'], [
            'name' => 'Admin WebHabit',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true,
        ]);

        User::updateOrCreate(['email' => 'igor@webhabit.com'], [
            'name' => 'Igor Londero',
            'password' => bcrypt('password'),
            'role' => 'user',
            'active' => true,
        ]);
    }
}
