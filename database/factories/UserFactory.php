<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail();

        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => \Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have the admin role.
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('admin');
        });
    }

    /**
     * Indicate that the user should have the student role.
     */
    public function student(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('student');
        });
    }

    /**
     * Indicate that the user should have the teacher role.
     */
    public function teacher(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('teacher');
        });
    }

    /**
     * Indicate that the user should have the internship_manager role.
     */
    public function internshipManager(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('internship_manager');
        });
    }
}






