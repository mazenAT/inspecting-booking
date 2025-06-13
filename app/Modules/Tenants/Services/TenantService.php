<?php

namespace App\Modules\Tenants\Services;

use App\Services\BaseService;
use App\Modules\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class TenantService extends BaseService
{
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }

    public function getTeams(int $tenantId): Collection
    {
        return $this->model->find($tenantId)->teams;
    }

    public function getUsers(int $tenantId): Collection
    {
        return $this->model->find($tenantId)->users;
    }

    public function getBookings(int $tenantId): Collection
    {
        return $this->model->find($tenantId)->bookings;
    }

    public function updateSettings(int $tenantId, array $settings): bool
    {
        $tenant = $this->model->find($tenantId);
        $currentSettings = $tenant->settings ?? [];
        $newSettings = array_merge($currentSettings, $settings);
        
        return $tenant->update(['settings' => $newSettings]);
    }
} 