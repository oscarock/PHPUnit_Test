<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function create(array $data);
    public function delete($id);
}