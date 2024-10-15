<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'phone' => '1234567890',
            'email' => 'admin@localhost',
            'password' => bcrypt('password'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'User',
            'phone' => '1234567891',
            'email' => 'user@localhost',
            'password' => bcrypt('password'),
            'is_admin' => false
        ]);
    }
}
