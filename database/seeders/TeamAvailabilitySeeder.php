<?php

namespace Database\Seeders;

use App\Modules\Availability\Models\TeamAvailability;
use Illuminate\Database\Seeder;

class TeamAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        // Set availability for Team A (team_id = 1)
        for ($day = 1; $day <= 5; $day++) { // Monday to Friday
            TeamAvailability::create([
                'team_id' => 1,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'is_available' => true
            ]);
        }

        // Set availability for Team B (team_id = 2)
        for ($day = 1; $day <= 5; $day++) { // Monday to Friday
            TeamAvailability::create([
                'team_id' => 2,
                'day_of_week' => $day,
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'is_available' => true
            ]);
        }

        // Set availability for Quality Control Team (team_id = 3)
        for ($day = 1; $day <= 5; $day++) { // Monday to Friday
            TeamAvailability::create([
                'team_id' => 3,
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'is_available' => true
            ]);
        }
    }
} 