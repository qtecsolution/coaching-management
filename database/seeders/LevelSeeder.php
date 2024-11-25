<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'Class 1'],
            ['name' => 'Class 2'],
            ['name' => 'Class 3'],
            ['name' => 'Class 4'],
            ['name' => 'Class 5'],
            ['name' => 'Class 6'],
            ['name' => 'Class 7'],
            ['name' => 'Class 8'],
            ['name' => 'Class 9'],
            ['name' => 'Class 10'],
            ['name' => 'HSC 1'],
            ['name' => 'HSC 2'],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}
