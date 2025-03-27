<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        try {
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
                throw new Exception("Resource is already reserved in this time slot");
            }
    
            $reservation = Reservation::create($request->all());
            return response()->json([
                "status" => 201,
                "data" => $reservation
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "details" => $e->getMessage()
            ], 500);
        }

        
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
            return response()->json([
                "status" => 200,
                "data" => "Reservation cancelled successful"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "details" => $e->getMessage()
            ], 500);
        }

    }
}
