<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $deegrees = ['JSC', 'SSC', 'HSC', 'DIPLOMA', 'BSC', 'MSC'];

        return [
            'reg_id' => Str::random(8) . rand(1, 9),
            'occupation' => fake()->jobTitle(),
            'qualification' => fake()->randomElement($deegrees),
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
