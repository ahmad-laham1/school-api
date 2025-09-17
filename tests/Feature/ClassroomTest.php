<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_classroom()
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();

        $this->actingAs($admin, 'api');

        $response = $this->postJson('/api/classrooms', [
            'name' => 'Math 101',
            'description' => 'Basic Algebra',
            'teacher_id' => $teacher->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data'=>['id', 'name', 'teacher']]);
    }

    /** @test */
    public function teacher_cannot_create_classroom()
    {
        $teacher = User::factory()->teacher()->create();
        $this->actingAs($teacher, 'api');

        $response = $this->postJson('/api/classrooms', [
            'name' => 'Science 101',
            'description' => 'Physics',
            'teacher_id' => $teacher->id,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_list_classrooms()
    {
        $admin = User::factory()->admin()->create();
        Classroom::factory()->count(2)->create();

        $this->actingAs($admin, 'api');
        $response = $this->getJson('/api/classrooms');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }
}