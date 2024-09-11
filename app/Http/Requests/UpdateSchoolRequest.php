<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolRequest extends FormRequest
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
        $schoolId = $this->route('id');
        
        return [
            'name' => 'sometimes|string',
            'address' => 'sometimes|string',
            'phone_number' => ['sometimes', 'string' , 'regex:/^09\d{8}$/',
            Rule::unique('schools', 'phone_number')->ignore($schoolId)]
        ];
    }
}
