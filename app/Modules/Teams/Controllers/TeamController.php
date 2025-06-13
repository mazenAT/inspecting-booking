<?php

namespace App\Modules\Teams\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teams\Models\Team;
use App\Modules\Availability\Models\TeamAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }

    public function show(int $id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $team = Team::create($request->all());
        return response()->json($team, 201);
    }

    public function update(Request $request, int $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $team->update($request->all());
        return response()->json($team);
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
} 