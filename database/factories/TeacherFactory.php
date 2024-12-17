<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'teacher_id' => fake()->numberBetween(1000, 9999),
            'school_name' => fake()->company(),
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
