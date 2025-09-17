<?php

namespace Database\Factories;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => (static::$password ??= 'password123'),
            'role' => fake()->randomElement([UserRole::Admin, UserRole::Teacher, UserRole::Student]),
        ];
    }

    public function admin()
    {
        return $this->state(fn() => ['role' => UserRole::Admin]);
    }

    public function teacher()
    {
        return $this->state(fn() => ['role' => UserRole::Teacher]);
    }

    public function student()
    {
        return $this->state(fn() => ['role' => UserRole::Student]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'email_verified_at' => null,
            ],
        );
    }
}