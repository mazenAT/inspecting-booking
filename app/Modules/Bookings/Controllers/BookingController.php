<?php

namespace App\Modules\Bookings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Bookings\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings);
    }

    public function show(int $id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string'
        ]);

        // Check if the time slot is available
        $team = \App\Modules\Teams\Models\Team::findOrFail($request->team_id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        $availability = $team->availability()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return response()->json(['message' => 'Team is not available on this day'], 400);
        }

        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);
        $availabilityStart = Carbon::parse($availability->start_time);
        $availabilityEnd = Carbon::parse($availability->end_time);

        if (!$startTime->between($availabilityStart, $availabilityEnd) ||
            !$endTime->between($availabilityStart, $availabilityEnd)) {
            return response()->json(['message' => 'Time slot is not within team availability'], 400);
        }

        // Check for existing bookings
        $existingBooking = Booking::where('team_id', $request->team_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime]);
            })
            ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'Time slot is already booked'], 400);
        }

        $booking = Booking::create($request->all());
        return response()->json($booking, 201);
    }

    public function update(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string'
        ]);

        $booking->update($request->all());
        return response()->json($booking);
    }

    public function destroy(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return response()->json(null, 204);
    }

    public function getByTenant(int $tenantId)
    {
        $bookings = Booking::where('tenant_id', $tenantId)->get();
        return response()->json($bookings);
    }

    public function getByTeam(int $teamId)
    {
        $bookings = Booking::where('team_id', $teamId)->get();
        return response()->json($bookings);
    }

    public function getByUser(int $userId)
    {
        $bookings = Booking::where('user_id', $userId)->get();
        return response()->json($bookings);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json($booking);
    }

    public function getUpcomingBookings(Request $request, int $tenantId)
    {
        $request->validate([
            'days' => 'integer|min:1|max:30'
        ]);

        $days = $request->input('days', 7);
        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addDays($days);

        $bookings = Booking::where('tenant_id', $tenantId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return response()->json($bookings);
    }
} 