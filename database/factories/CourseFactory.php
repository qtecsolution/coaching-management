<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = [
            'Web Design', 'Web Development', 'MERN Development', 'iOS Development', 'Flutter Development',
            'Laravel Development', 'Mastering Digital Marketing', 'Local SEO', 'Social Media Marketing',
        ];

        return [
            'title' => fake()->randomElement($courses),
            'slug' => fake()->slug(),
            'description' => fake()->text(),
            'image' => 'https://placehold.co/600x400/png',
            'status' => fake()->randomElement([1, 0])
        ];
    }
}
