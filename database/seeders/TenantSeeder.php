<?php

namespace Database\Seeders;

use App\Modules\Tenants\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'name' => 'Demo Company',
            'domain' => 'demo.local',
            'database' => 'demo_db',
            'settings' => [
                'timezone' => 'UTC',
                'currency' => 'USD',
                'language' => 'en'
            ]
        ]);

        Tenant::create([
            'name' => 'Test Company',
            'domain' => 'test.local',
            'database' => 'test_db',
            'settings' => [
                'timezone' => 'UTC',
                'currency' => 'EUR',
                'language' => 'en'
            ]
        ]);
    }
} 