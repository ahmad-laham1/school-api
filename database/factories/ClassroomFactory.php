<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Class',
            'description' => fake()->sentence(),
            'teacher_id' =>  fake()->randomElement(User::where('role', UserRole::Teacher)->pluck('id')->toArray()) ?? User::factory()->teacher()->create()->id, // auto teacher
        ];
    }
}