<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Remove existing config/mail.php file
        if (file_exists(base_path('config/mail.php'))) {
            unlink(base_path('config/mail.php'));
        }

        // copy mail-config.example to config/mail.php
        copy(base_path('mail-config.example'), base_path('config/mail.php'));

        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class,
            CourseSeeder::class,
            BatchSeeder::class,
            StudentBatchSeeder::class,
            SettingSeeder::class,
            PaymentSeeder::class
        ]);
    }
}
