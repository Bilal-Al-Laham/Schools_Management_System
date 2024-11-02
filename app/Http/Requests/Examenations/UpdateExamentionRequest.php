<?php

namespace App\Http\Requests\Examenations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamentionRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:200'],
            'subject_id' => ['nullable', 'integer', 'exists:users,id'],
            'school_class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date'],
            'type' => ['nullable', 'string', 'max:200', 'in:chapter tests,midterm Exam , final Exam'],
        ];
    }
}
