<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        if ($reservation) {
            $reservation->delete();
            return true;
        }
        return false;
    }
}
