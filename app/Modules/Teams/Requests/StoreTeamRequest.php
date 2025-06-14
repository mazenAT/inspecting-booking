<?php

namespace App\Modules\Teams\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Team name is required.',
            'name.max' => 'Team name cannot exceed 255 characters.',
            'specialization.required' => 'Team specialization is required.',
            'specialization.max' => 'Specialization cannot exceed 255 characters.'
        ];
    }
} 