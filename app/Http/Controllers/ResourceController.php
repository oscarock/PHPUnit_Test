<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;

class ResourceController extends Controller
{
    public function index()
    {
        try {
            $resources = Resource::all();
    
            return response()->json([
                "status" => 200,
                "data" => $resources
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "details" => $e->getMessage()
            ], 500);
        }
    }

    public function availability($id, Request $request)
    {
        try {
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
    
            return response()->json([
                "status" => 200,
                "data" => ["available" => !$conflict]
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "details" => $e->getMessage()
            ], 500);
        }

    }
}
