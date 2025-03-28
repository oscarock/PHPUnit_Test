<?php

namespace App\Repositories;

use App\Models\Resource;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Repositories\Interfaces\ResourceRepositoryInterface;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function getAll()
    {
        return Resource::all();
    }

    public function checkAvailability($id, $reservedAt, $duration)
    {
        $resource = Resource::find($id);
        if (!$resource) {
            return false;
        }

        $startTime = Carbon::parse($reservedAt);
        $endTime = $startTime->copy()->addMinutes((int) $duration);

        return Reservation::where('resource_id', $id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('reserved_at', [$startTime, $endTime])
                      ->orWhereBetween('reserved_at', [$startTime, $endTime]);
        })->exists();
    }
}
