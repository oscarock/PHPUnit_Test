<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Exception;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Helpers\ResponseHelper;

class ReservationController extends Controller
{
    protected $reservationRepository;
    protected $resourceRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository, ResourceRepositoryInterface $resourceRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->resourceRepository = $resourceRepository;
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'resource_id' => 'required|exists:resources,id',
                'reserved_at' => 'required|date',
                'duration' => 'required|integer|min:1'
            ]);

            $conflict = $this->resourceRepository->checkAvailability($request->resource_id, $request->reserved_at, $request->duration);
    
            if ($conflict) {
                throw new Exception("Resource is already reserved in this time slot");
            }
    
            $reservation = $this->reservationRepository->create($request->all());
            return ResponseHelper::success($reservation);
        } catch (Exception $e) {
            return ResponseHelper::error($e);
        }
    }

    public function destroy($id)
    {
        try {
            $reservation = $this->reservationRepository->delete($id);
            if (!$reservation) {
                throw new Exception("Reservation ID dont exist");
            }
            return ResponseHelper::success("Reservation cancelled successful");
        } catch (Exception $e) {
            return ResponseHelper::error($e);
        }
    }
}
