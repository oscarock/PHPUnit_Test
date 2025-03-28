<?php

namespace App\Repositories\Interfaces;

interface ResourceRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function checkAvailability($id, $reservedAt, $duration);
}