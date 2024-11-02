<?php

namespace App\Http\Requests\Examenations;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamenationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:200'],
            'subject_id' => ['required', 'integer', 'exists:users,id'],
            'school_class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date'],
            'type' => ['required', 'string', 'max:200', 'in:chapter tests,midterm Exam , final Exam'],
        ];
    }
}
