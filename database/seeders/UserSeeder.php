<?php

namespace Database\Seeders;

use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user for Demo Company
        User::create([
            'tenant_id' => 1,
            'name' => 'Demo Admin',
            'email' => 'admin@demo.local',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create regular user for Demo Company
        User::create([
            'tenant_id' => 1,
            'name' => 'Demo User',
            'email' => 'user@demo.local',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create admin user for Test Company
        User::create([
            'tenant_id' => 2,
            'name' => 'Test Admin',
            'email' => 'admin@test.local',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
} 