<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRequest extends FormRequest
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
            'student_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'payment_date' => 'nullable|date',
            'due_date' => 'required|date',
            'status' => 'required|in:is paid,is not paid',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ];
    }
}
