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
        return [
            'title' => fake()->jobTitle(),
            'slug' => fake()->slug(),
            'description' => fake()->text(),
            'image' => 'https://placehold.co/600x400/png',
            'price' => fake()->randomFloat(2, 0, 8000),
            'discount_type' => fake()->randomElement(['fixed', 'percentage']),
            'discount' => fake()->randomFloat(2, 0, 100),
            'status' => fake()->randomElement([1, 0])
        ];
    }
}
