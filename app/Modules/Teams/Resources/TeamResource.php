<?php

namespace App\Modules\Teams\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialization' => $this->specialization,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'availability' => $this->whenLoaded('availability', function() {
                return $this->availability->map(function($availability) {
                    return [
                        'id' => $availability->id,
                        'day_of_week' => $availability->day_of_week,
                        'start_time' => $availability->start_time,
                        'end_time' => $availability->end_time,
                        'is_available' => $availability->is_available
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 