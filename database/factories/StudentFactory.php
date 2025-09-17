<?php

namespace Database\Factories;

use App\Models\Classroom;
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
            'user_id' => \App\Models\User::factory()->student(),
            'date_of_birth' => fake()->date('Y-m-d', '-10 years'),
            'classroom_id' => fake()->randomElement(Classroom::all()->pluck('id')->toArray()) ?? Classroom::factory()->create(), // auto classroom
        ];
    }
}