<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'teacher_id' => $this->teacher_id, // 👈 add this line
            'teacher' => $this->teacher
                ? [
                    'id' => $this->teacher->id,
                    'name' => $this->teacher->name,
                    'email' => $this->teacher->email,
                ]
                : null,
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'created_at' => $this->created_at,
        ];
    }
}
