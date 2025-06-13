<?php

namespace App\Modules\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Tenants\Models\Tenant;
use App\Modules\Teams\Models\Team;
use App\Modules\Users\Models\User;

class Booking extends Model
{
    protected $fillable = [
        'tenant_id',
        'team_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 