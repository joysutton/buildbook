<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|string|max:255',
            'est_cost' => 'nullable|integer|min:0',
            'actual_cost' => 'nullable|integer|min:0',
            'source' => 'nullable|string|max:255',
            'acquired' => 'boolean',
            'share' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'est_cost.integer' => 'Estimated cost must be a whole number (in cents).',
            'actual_cost.integer' => 'Actual cost must be a whole number (in cents).',
        ];
    }
} 