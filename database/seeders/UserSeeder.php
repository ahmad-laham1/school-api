<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
        // Admin
        User::factory()->create([
            'name' => 'Alice Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => UserRole::Admin,
        ]);

        // Teacher
        User::factory()->create([
            'name' => 'Tom Teacher',
            'email' => 'teacher@example.com',
            'password' => 'password123',
            'role' => UserRole::Teacher,
        ]);


    }
}
