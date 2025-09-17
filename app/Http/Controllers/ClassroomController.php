<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Classroom::class, 'classroom');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            // Teacher → only classrooms they teach
            $classrooms = Classroom::with('teacher', 'students')->where('teacher_id', $user->id)->paginate(10);
        } elseif ($user->isStudent()) {
            // Student → only the classroom(s) they belong to
            $classrooms = Classroom::with('teacher', 'students')
                ->whereHas('students', function ($query) use ($user) {
                    $query->where('user_id', $user->id); // ✅ fix here
                })
                ->paginate(10);
        } else {
            // Admin → all classrooms
            $classrooms = Classroom::with('teacher', 'students')->paginate(10);
        }

        return ClassroomResource::collection($classrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        $data = $request->validated();

        if(!Auth::user()->isAdmin()) {
            $data['teacher_id'] = Auth::user()->id;
        }
        $classroom = Classroom::create($data);
        return new ClassroomResource($classroom->load('teacher', 'students'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        return new ClassroomResource($classroom->load('teacher', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return response()->json(['message' => 'Classroom updated successfully', 'classroom' => new ClassroomResource($classroom->load('teacher', 'students'))]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return response()->json(['message' => 'Classroom deleted successfully']);
    }

 public function removeStudent(Classroom $classroom, Student $student)
{
    // Ensure this student actually belongs to that classroom
    if ($student->classroom_id !== $classroom->id) {
        return response()->json(['message' => 'Student not in this class'], 400);
    }

    // Only admin or the teacher of this class can remove
    $user = Auth::user();
    if (!($user->isAdmin() || ($user->isTeacher() && $classroom->teacher_id === $user->id))) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    // Remove student from class (set null instead of delete)
    $student->update(['classroom_id' => null]);

    return response()->json(['message' => 'Student removed from class']);
}

}