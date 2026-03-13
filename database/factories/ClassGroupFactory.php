<?php

namespace Database\Factories;

use App\Models\ClassGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassGroup>
 */
class ClassGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeLevel = $this->faker->randomElement(['1', '2', '3', '4', 'A', 'B', 'C']);
        
        return [
            'name' => $gradeLevel . ($this->faker->randomElement(['st', 'nd', 'rd', 'th']) . ' Grade') . ' - Class ' . $this->faker->randomLetter(),
            'grade_level' => $gradeLevel,
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate the class is for 1st grade.
     */
    public function grade1(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade_level' => '1',
            'name' => '1st Grade Class',
        ]);
    }

    /**
     * Indicate the class is for 2nd grade.
     */
    public function grade2(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade_level' => '2',
            'name' => '2nd Grade Class',
        ]);
    }

    /**
     * Indicate the class is for 3rd grade.
     */
    public function grade3(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade_level' => '3',
            'name' => '3rd Grade Class',
        ]);
    }
}
