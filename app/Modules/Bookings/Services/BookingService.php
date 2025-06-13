<?php

namespace App\Modules\Bookings\Services;

use App\Services\BaseService;
use App\Modules\Bookings\Models\Booking;
use App\Modules\Teams\Services\TeamService;
use Illuminate\Database\Eloquent\Collection;

class BookingService extends BaseService
{
    protected TeamService $teamService;

    public function __construct(Booking $model, TeamService $teamService)
    {
        parent::__construct($model);
        $this->teamService = $teamService;
    }

    public function getByTenant(int $tenantId): Collection
    {
        return $this->model->where('tenant_id', $tenantId)->get();
    }

    public function getByTeam(int $teamId): Collection
    {
        return $this->model->where('team_id', $teamId)->get();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function create(array $data): ?Booking
    {
        // Check if the team is available for the requested time slot
        if (!$this->teamService->isAvailable(
            $data['team_id'],
            $data['date'],
            $data['start_time'],
            $data['end_time']
        )) {
            return null;
        }

        // Check if there are any overlapping bookings
        $overlappingBooking = $this->model
            ->where('team_id', $data['team_id'])
            ->where('date', $data['date'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']]);
            })
            ->first();

        if ($overlappingBooking) {
            return null;
        }

        return $this->model->create($data);
    }

    public function updateStatus(int $bookingId, string $status): bool
    {
        return $this->update($bookingId, ['status' => $status]);
    }

    public function getUpcomingBookings(int $tenantId, int $days = 7): Collection
    {
        $startDate = now();
        $endDate = now()->addDays($days);

        return $this->model
            ->where('tenant_id', $tenantId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }
} 