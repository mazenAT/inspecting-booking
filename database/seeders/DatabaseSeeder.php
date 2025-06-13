<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,
            TeamSeeder::class,
            TeamAvailabilitySeeder::class,
            UserSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
