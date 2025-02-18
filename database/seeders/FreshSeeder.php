<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreshSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove existing config/mail.php file
        if (file_exists(base_path('config/mail.php'))) {
            unlink(base_path('config/mail.php'));
        }

        // copy mail-config.example to config/mail.php
        copy(base_path('mail-config.example'), base_path('config/mail.php'));
        
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin',
            'phone' => '1234567890',
            'email' => 'admin@localhost',
            'password' => bcrypt('password'),
            'user_type' => 'admin'
        ]);

        // Create fake teachers
        $teachers = User::factory(1)->create([
            'user_type' => 'teacher'
        ]);

        // Create a teacher record for each teacher user
        $teachers->each(function ($teacherUser) {
            Teacher::factory()->create(['user_id' => $teacherUser->id]);
        });

        // Ensure the first student's information remains fixed for login purposes
        $teacher = User::where('user_type', 'teacher')->first();
        if ($teacher) {
            $teacher->phone = '1234567891';
            $teacher->name = 'Teacher';
            $teacher->email = 'teacher@localhost';
            $teacher->password = bcrypt('password');
            $teacher->save();
        }

        // Execute the remaining seeders
        $this->call([
            RolePermissionSeeder::class,
            SettingSeeder::class
        ]);
    }
}
