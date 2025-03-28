<?php

namespace App\Repositories\Interfaces;

interface ResourceRepositoryInterface
{
    public function getAll();
    public function checkAvailability($id, $reservedAt, $duration);
}