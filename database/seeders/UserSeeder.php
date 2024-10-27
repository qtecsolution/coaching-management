<?php

namespace Database\Seeders;

use App\Models\Student;
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
            'user_type' => 'admin'
        ]);

        // Create fake users
        $users = User::factory(50)->create();

        // Create a student for each user
        $users->each(function ($user) {
            Student::factory()->create(['user_id' => $user->id]);
        });
    }
}
