<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Carbon\Carbon;

class ResourceController extends Controller
{
    public function index()
    {
        return response()->json(Resource::all());   
    }

    public function availability($id, Request $request)
    {
        $request->validate([
            'reserved_at' => 'required|date',
            'duration' => 'required|integer|min:1'
        ]);

        $startTime = Carbon::parse($request->reserved_at);
        $endTime = $startTime->copy()->addMinutes((int) $request->duration);

        $conflict = Reservation::where('resource_id', $id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('reserved_at', [$startTime, $endTime])
                      ->orWhereBetween('reserved_at', [$startTime, $endTime]);
            })->exists();

        return response()->json(['available' => !$conflict]);
    }
}
