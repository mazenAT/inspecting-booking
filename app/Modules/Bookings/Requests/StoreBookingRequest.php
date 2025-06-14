<?php

namespace App\Modules\Bookings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_id' => 'required|exists:tenants,id',
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
            'date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($this->date);
                    $startTime = Carbon::parse($value);
                    $endTime = Carbon::parse($this->end_time);

                    if ($startTime->greaterThanOrEqualTo($endTime)) {
                        $fail('Start time must be before end time.');
                    }
                }
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time'
            ],
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'tenant_id.required' => 'Please select a tenant.',
            'tenant_id.exists' => 'The selected tenant is invalid.',
            'team_id.required' => 'Please select a team.',
            'team_id.exists' => 'The selected team is invalid.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user is invalid.',
            'date.required' => 'Please select a date.',
            'date.after_or_equal' => 'The date must be today or a future date.',
            'start_time.required' => 'Please select a start time.',
            'start_time.date_format' => 'The start time format is invalid.',
            'end_time.required' => 'Please select an end time.',
            'end_time.date_format' => 'The end time format is invalid.',
            'end_time.after' => 'The end time must be after the start time.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.'
        ];
    }
} 