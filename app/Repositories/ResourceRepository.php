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

    public function findById($id)
    {
        return Resource::find($id);
    }

    public function checkAvailability($id, $reservedAt, $duration)
    {
        $resource = Resource::find($id);
        if (!$resource) {
            return false;
        }
        
        return !$resource->reservations()
        ->where('reserved_at', '<=', $reservedAt)
        ->whereRaw('DATE_ADD(reserved_at, INTERVAL duration MINUTE) > ?', [$reservedAt])
        ->exists();
    }
}
