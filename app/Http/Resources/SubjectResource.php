<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'school_class_id' => $this->school_class_id,
            'teacher_id' => $this->teacher_id,
            'teacher' => new TeacherResource($this->teacher),
            'school_class' => new school_class_Resource($this->school_class),
        ];
    }
}
