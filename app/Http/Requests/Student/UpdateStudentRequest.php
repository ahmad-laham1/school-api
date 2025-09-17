<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
    return [
        'name'         => 'sometimes|string|max:255',
        'email'        => 'sometimes|email|unique:users,email,' . $this->student->user_id,
        'date_of_birth'=> 'sometimes|date',
        'classroom_id' => 'sometimes|exists:classrooms,id',
    ];
}

}
