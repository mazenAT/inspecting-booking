<?php

namespace App\Modules\Tenants\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Teams\Models\Team;
use App\Modules\Users\Models\User;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'database',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
} 