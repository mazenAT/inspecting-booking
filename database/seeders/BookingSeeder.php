<?php

namespace Database\Seeders;

use App\Modules\Bookings\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Create some sample bookings for Demo Company
        Booking::create([
            'tenant_id' => 1,
            'team_id' => 1,
            'user_id' => 1,
            'date' => now()->addDays(2),
            'start_time' => now()->addDays(2)->setTime(10, 0),
            'end_time' => now()->addDays(2)->setTime(11, 0),
            'status' => 'pending',
            'notes' => 'Initial inspection booking'
        ]);

        Booking::create([
            'tenant_id' => 1,
            'team_id' => 2,
            'user_id' => 2,
            'date' => now()->addDays(3),
            'start_time' => now()->addDays(3)->setTime(14, 0),
            'end_time' => now()->addDays(3)->setTime(15, 0),
            'status' => 'confirmed',
            'notes' => 'Follow-up inspection'
        ]);

        // Create a sample booking for Test Company
        Booking::create([
            'tenant_id' => 2,
            'team_id' => 3,
            'user_id' => 3,
            'date' => now()->addDays(4),
            'start_time' => now()->addDays(4)->setTime(9, 0),
            'end_time' => now()->addDays(4)->setTime(10, 0),
            'status' => 'pending',
            'notes' => 'Quality control inspection'
        ]);
    }
} 