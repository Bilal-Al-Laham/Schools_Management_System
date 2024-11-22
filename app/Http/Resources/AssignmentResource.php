<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'subject_name' => new SubjectResource($this->whenLoaded('subject')) ?? 'no subject',
            'section_name' => new SubjectResource($this->whenLoaded('section')) ?? 'no section',
            'teacher_name' => new SubjectResource($this->whenLoaded('teacher')) ?? 'no teacher',
        ];
    }
}
