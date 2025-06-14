<?php

namespace App\Modules\Tenants\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tenant name is required.',
            'name.max' => 'Tenant name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'address.required' => 'Address is required.'
        ];
    }
} 