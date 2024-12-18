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
            'reg_id' => fake()->numberBetween(100, 999) . rand(0, 9),
            'occupation' => fake()->jobTitle(),
            'qualification' => fake()->jobTitle(),
            'date_of_birth' => fake()->date(),
            'nid_number' => fake()->numberBetween(10000000, 99999999),
            'address' => fake()->address(),
            'father_name' => fake()->name(),
            'mother_name' => fake()->name(),
            'emergency_contact' => json_encode(
                [
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber()
                ]
                ),
                'created_by' => 1,
                'updated_by' => 1
        ];
    }
}
