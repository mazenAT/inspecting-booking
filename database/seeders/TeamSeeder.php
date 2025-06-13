<?php

namespace Database\Seeders;

use App\Modules\Teams\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // Create teams for Demo Company (tenant_id = 1)
        Team::create([
            'tenant_id' => 1,
            'name' => 'Inspection Team A',
            'description' => 'Main inspection team for Demo Company',
            'is_active' => true
        ]);

        Team::create([
            'tenant_id' => 1,
            'name' => 'Inspection Team B',
            'description' => 'Secondary inspection team for Demo Company',
            'is_active' => true
        ]);

        // Create teams for Test Company (tenant_id = 2)
        Team::create([
            'tenant_id' => 2,
            'name' => 'Quality Control Team',
            'description' => 'Main quality control team for Test Company',
            'is_active' => true
        ]);
    }
} 