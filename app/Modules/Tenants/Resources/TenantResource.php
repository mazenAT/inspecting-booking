<?php

namespace App\Modules\Tenants\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => $this->is_active,
            'bookings' => $this->whenLoaded('bookings', function() {
                return $this->bookings->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'date' => $booking->date,
                        'start_time' => $booking->start_time,
                        'end_time' => $booking->end_time,
                        'status' => $booking->status
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 