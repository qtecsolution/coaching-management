<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Str::random(8) . rand(1, 9),
            'nid_number' => fake()->numberBetween(10000000, 99999999),
            'address' => fake()->address(),
            'emergency_contact' => json_encode(
                [
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber()
                ]
            )
        ];
    }
}
