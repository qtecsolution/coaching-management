<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            'Web Design', 'Web Development', 'MERN Development', 'iOS Development', 'Flutter Development',
            'Laravel Development', 'Mastering Digital Marketing', 'Local SEO', 'Social Media Marketing',
        ];

        foreach ($courses as $course) {
            Course::create([
                'title' => $course,
                'slug' => strtolower(str_replace(' ', '-', $course)),
                'description' => $course,
                'image' => 'https://placehold.co/600x400.png'
            ]);
        }
    }
}
