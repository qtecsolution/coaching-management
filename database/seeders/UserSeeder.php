<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
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
        // Create an admin user
        User::create([
            'name' => 'Admin',
            'phone' => '1234567890',
            'email' => 'admin@localhost',
            'password' => bcrypt('password'),
            'user_type' => 'admin'
        ]);

        // Create fake students
        $students = User::factory(50)->create([
            'user_type' => 'student'
        ]);

        // Create a student record for each student user
        $students->each(function ($studentUser) {
            Student::factory()->create(['user_id' => $studentUser->id]);
        });

        // Create fake teachers
        $teachers = User::factory(50)->create([
            'user_type' => 'teacher'
        ]);

        // Create a teacher record for each teacher user
        $teachers->each(function ($teacherUser) {
            Teacher::factory()->create(['user_id' => $teacherUser->id]);
        });
    }
}
