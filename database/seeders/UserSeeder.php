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

        // Ensure the first student's information remains fixed for login purposes
        $user = User::where('user_type', 'student')->first();
        $user->phone = '0123456789';
        $user->name = 'Student 1';
        $user->email = 'student_1@localhost';
        $user->password = bcrypt('password');
        $user->save();
        // Ensure the first student's information remains fixed for login purposes
        $teacher = User::where('user_type',
            'teacher'
        )->first();
        $teacher->phone = '1234567891';
        $teacher->name = 'Teacher 1';
        $teacher->email = 'teacher_1@localhost';
        $teacher->password = bcrypt('password');
        $teacher->save();

    }
}
