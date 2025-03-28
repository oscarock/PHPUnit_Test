<?php

namespace App\Factories;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Exception;

class ReservationFactory
{
    protected $resourceRepository;
    protected $reservationRepository;

    public function __construct(
        ResourceRepositoryInterface $resourceRepository,
        ReservationRepositoryInterface $reservationRepository
    ) {
        $this->resourceRepository = $resourceRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function create(array $data)
    {
        // Validar si el recurso existe
        $resource = $this->resourceRepository->findById($data['resource_id']);
        if (!$resource) {
          throw new Exception('Reservation ID dont exist');
        }

        // Validar si está disponible
        $isAvailable = $this->resourceRepository->checkAvailability(
            $data['resource_id'],
            $data['reserved_at'],
            $data['duration']
        );

        if (!$isAvailable) {
          throw new Exception("Resource is already reserved in this time slot");
        }

        // Crear la reserva
        return $this->reservationRepository->create($data);
    }
}
