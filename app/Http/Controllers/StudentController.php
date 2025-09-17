<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Student::class, 'student');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            // Teacher → only their students
            $students = Student::with('classroom')
                ->whereHas('classroom', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->paginate(10);
        } elseif ($user->isStudent()) {
            // Student → only their own record
            $students = Student::with('classroom')->where('user_id', $user->id)->paginate(10);
        } else {
            // Admin → all students
            $students = Student::with('classroom')->paginate(10);
        }

        return StudentResource::collection($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        // Create user account for the student
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => 'password', // Default password, should be changed later
            'role' => UserRole::Student,
        ]);

        // Create student profile linked to the user
        $student = Student::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'classroom_id' => $request->classroom_id,
        ]);
        return new StudentResource($student->load('classroom'))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return new StudentResource($student->load('classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated();

        // Update linked user safely
        if ($student->user) {
            $student->user->update([
                'name' => $validated['name'] ?? $student->user->name,
                'email' => $validated['email'] ?? $student->user->email,
            ]);
        }

        // Update student-specific fields (only if present in validated data)
        $student->update([
            'date_of_birth' => $validated['date_of_birth'] ?? $student->date_of_birth,
            'classroom_id' => $validated['classroom_id'] ?? $student->classroom_id,
        ]);

        return response()->json([
            'message' => 'Student updated successfully',
            'student' => new StudentResource($student->load('classroom')),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }

public function profile()
{
    $user = Auth::user();

    if (!$user->student) {
        return response()->json([
            'message' => 'No student profile found for this user.'
        ], 403);
    }

    return new StudentResource($user->student->load('classroom'));
}

public function updateProfile(Request $request)
{
    $user = Auth::user();
    $student = $user->student;

    if (!$student) {
        return response()->json(['message' => 'Not a student'], 403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $student->user->id,
        'date_of_birth' => 'nullable|date',
    ]);

    // update user table
    $student->user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
    ]);

    // update student table
    $student->update([
        'date_of_birth' => $validated['date_of_birth'] ?? $student->date_of_birth,
    ]);

    return response()->json([
        'message' => 'Profile updated successfully',
        'student' => new StudentResource($student->load('classroom')),
    ]);
}
}