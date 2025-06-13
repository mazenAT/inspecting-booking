<?php

namespace App\Modules\Teams\Services;

use App\Services\BaseService;
use App\Modules\Teams\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamService extends BaseService
{
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }

    public function getAvailability(int $teamId): Collection
    {
        return $this->model->find($teamId)->availability;
    }

    public function getBookings(int $teamId): Collection
    {
        return $this->model->find($teamId)->bookings;
    }

    public function isAvailable(int $teamId, string $date, string $startTime, string $endTime): bool
    {
        $team = $this->model->find($teamId);
        $dayOfWeek = date('N', strtotime($date));
        
        $availability = $team->availability()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return false;
        }

        $startTimeObj = \DateTime::createFromFormat('H:i:s', $startTime);
        $endTimeObj = \DateTime::createFromFormat('H:i:s', $endTime);
        $availabilityStart = \DateTime::createFromFormat('H:i:s', $availability->start_time);
        $availabilityEnd = \DateTime::createFromFormat('H:i:s', $availability->end_time);

        return $startTimeObj >= $availabilityStart && $endTimeObj <= $availabilityEnd;
    }

    public function getAvailableTimeSlots(int $teamId, string $date): array
    {
        $team = $this->model->find($teamId);
        $dayOfWeek = date('N', strtotime($date));
        
        $availability = $team->availability()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return [];
        }

        $bookings = $team->bookings()
            ->where('date', $date)
            ->get();

        $availableSlots = [];
        $currentTime = \DateTime::createFromFormat('H:i:s', $availability->start_time);
        $endTime = \DateTime::createFromFormat('H:i:s', $availability->end_time);
        $interval = new \DateInterval('PT1H'); // 1 hour intervals

        while ($currentTime < $endTime) {
            $slotStart = clone $currentTime;
            $slotEnd = clone $currentTime;
            $slotEnd->add($interval);

            $isAvailable = true;
            foreach ($bookings as $booking) {
                $bookingStart = \DateTime::createFromFormat('H:i:s', $booking->start_time);
                $bookingEnd = \DateTime::createFromFormat('H:i:s', $booking->end_time);

                if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableSlots[] = [
                    'start_time' => $slotStart->format('H:i:s'),
                    'end_time' => $slotEnd->format('H:i:s')
                ];
            }

            $currentTime->add($interval);
        }

        return $availableSlots;
    }
} 