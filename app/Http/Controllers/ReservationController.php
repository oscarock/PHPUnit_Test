<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'reserved_at' => 'required|date',
            'duration' => 'required|integer|min:1'
        ]);

        $startTime = Carbon::parse($request->reserved_at);
        $endTime = $startTime->copy()->addMinutes($request->duration);

        $conflict = Reservation::where('resource_id', $request->resource_id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('reserved_at', [$startTime, $endTime])
                      ->orWhereBetween('reserved_at', [$startTime, $endTime]);
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'Resource is already reserved in this time slot'], 400);
        }

        $reservation = Reservation::create($request->all());
        return response()->json($reservation, 201);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(['message' => 'Reservation cancelled successfully']);
    }
}
