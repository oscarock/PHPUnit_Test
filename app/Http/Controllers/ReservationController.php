<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Exception;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Factories\ReservationFactory;
use App\Helpers\ResponseHelper;

class ReservationController extends Controller
{
    protected $reservationRepository;
    protected $resourceRepository;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        ReservationFactory $reservationFactory
    )
    {
        $this->reservationRepository = $reservationRepository;
        $this->reservationFactory = $reservationFactory;
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'resource_id' => 'required|exists:resources,id',
                'reserved_at' => 'required|date',
                'duration' => 'required|integer|min:1'
            ]);
    
            $reservation = $this->reservationFactory->create($request->all());
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
