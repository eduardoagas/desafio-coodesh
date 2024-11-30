<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntriesSearchRequest extends FormRequest
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
            'search' => 'nullable|string|max:255',
            'cursor' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
        ];
    }
}
