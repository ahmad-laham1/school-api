<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Admin: global overview
    public function adminStats()
    {
        return response()->json([
            'total_students'  => Student::count(),
            'total_teachers'  => User::where('role', 'teacher')->count(),
            'total_classrooms'=> Classroom::count(),
            'latest_students' => Student::latest()->take(5)->with('user')->get(),
        ]);
    }

    // Teacher: only their classrooms & students
    public function teacherStats()
    {
        $teacher = Auth::user();

        $classrooms = Classroom::where('teacher_id', $teacher->id)
                               ->withCount('students')
                               ->get();

        return response()->json([
            'total_classrooms' => $classrooms->count(),
            'total_students'   => $classrooms->sum('students_count'),
            'classrooms'       => $classrooms,
        ]);
    }

    // Student: only their own profile + classroom
    public function studentStats()
    {
        $student = Auth::user()->student;

        return response()->json([
            'name'       => $student->user->name,
            'email'      => $student->user->email,
            'dob'        => $student->date_of_birth,
            'classroom'  => $student->classroom ? [
                'id'   => $student->classroom->id,
                'name' => $student->classroom->name,
                'teacher' => $student->classroom->teacher?->name,
            ] : null,
        ]);
    }
}
