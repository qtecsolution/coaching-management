<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reg_id' => fake()->numberBetween(100000, 999999),
            'school_name' => fake()->name() . ' School',
            'class' => fake()->numberBetween(1, 12),
            'date_of_birth' => fake()->date(),
            'address' => fake()->address(),
            'father_name' => fake()->name(),
            'mother_name' => fake()->name(),
            'emergency_contact' => json_encode(
                [
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber()
                ]
            )
        ];
    }
}
