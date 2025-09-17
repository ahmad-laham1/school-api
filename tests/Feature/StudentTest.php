<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_student()
    {
        $admin = User::factory()->admin()->create();
        $classroom = Classroom::factory()->create();


        $response = $this->authenticate($admin)->postJson('/api/students', [
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'date_of_birth' => '2010-05-10',
            'classroom_id' => $classroom->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data'=>['id', 'name', 'classroom']]);
    }

    /** @test */
    public function teacher_can_only_see_their_students()
    {
        $teacher = User::factory()->teacher()->create();
        $classroom = Classroom::factory()->for($teacher, 'teacher')->create();
        Student::factory()->count(3)->for($classroom)->create();

        
        $response = $this->authenticate($teacher)->getJson('/api/students');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }


}