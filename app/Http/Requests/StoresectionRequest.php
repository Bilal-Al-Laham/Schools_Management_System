<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoresectionRequest extends FormRequest
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
            'name' => 'required|string|exists:sections,name',
            'school_class_id' => 'required|integer'
            // 'school_class' => 'required|string|exists:school_classes,name'
        ];
    }
}
