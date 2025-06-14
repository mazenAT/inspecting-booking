<?php

namespace App\Modules\Bookings\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant' => [
                'id' => $this->tenant->id,
                'name' => $this->tenant->name,
                'email' => $this->tenant->email,
            ],
            'team' => [
                'id' => $this->team->id,
                'name' => $this->team->name,
                'specialization' => $this->team->specialization,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 