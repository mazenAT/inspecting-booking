<?php

namespace App\Modules\Teams\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teams\Models\Team;
use App\Modules\Teams\Requests\StoreTeamRequest;
use App\Modules\Teams\Resources\TeamResource;
use App\Modules\Availability\Models\TeamAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('availability')->get();
        return TeamResource::collection($teams);
    }

    public function show(int $id)
    {
        $team = Team::with('availability')->findOrFail($id);
        return new TeamResource($team);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create($request->validated());
        return new TeamResource($team);
    }

    public function update(Request $request, int $id)
    {
        $team = Team::findOrFail($id);
        $team->update($request->all());
        return new TeamResource($team);
    }

    public function destroy(int $id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return response()->json(null, 204);
    }

    public function getAvailability(int $id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team->availability);
    }

    public function getBookings(int $id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team->bookings);
    }

    public function checkAvailability(Request $request, int $id)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time'
        ]);

        $team = Team::findOrFail($id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        $availability = $team->availability()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return response()->json(['available' => false, 'message' => 'Team is not available on this day']);
        }

        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);
        $availabilityStart = Carbon::parse($availability->start_time);
        $availabilityEnd = Carbon::parse($availability->end_time);

        $isAvailable = $startTime->between($availabilityStart, $availabilityEnd) &&
                      $endTime->between($availabilityStart, $availabilityEnd);

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Time slot is available' : 'Time slot is not available'
        ]);
    }

    public function getAvailableTimeSlots(Request $request, int $id)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $team = Team::findOrFail($id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        $availability = $team->availability()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return response()->json(['slots' => []]);
        }

        $startTime = Carbon::parse($availability->start_time);
        $endTime = Carbon::parse($availability->end_time);
        $slots = [];

        while ($startTime->copy()->addMinutes(30)->lte($endTime)) {
            $slotStart = $startTime->copy();
            $slotEnd = $startTime->copy()->addMinutes(30);

            // Check if this slot is already booked
            $isBooked = $team->bookings()
                ->where('date', $date->format('Y-m-d'))
                ->where(function ($query) use ($slotStart, $slotEnd) {
                    $query->whereBetween('start_time', [$slotStart, $slotEnd])
                        ->orWhereBetween('end_time', [$slotStart, $slotEnd]);
                })
                ->exists();

            if (!$isBooked) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i')
                ];
            }

            $startTime->addMinutes(30);
        }

        return response()->json(['slots' => $slots]);
    }

    public function getActiveTeams()
    {
        $teams = Team::where('is_active', true)
            ->with('availability')
            ->get();
        return TeamResource::collection($teams);
    }
} 