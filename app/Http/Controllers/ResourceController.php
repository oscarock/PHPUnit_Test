<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use Exception;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Helpers\ResponseHelper;

class ResourceController extends Controller
{

    protected $resourceRepository;

    public function __construct(ResourceRepositoryInterface $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function index()
    {
        try {
            $resources = $this->resourceRepository->getAll();
            return ResponseHelper::success($resources);
        } catch (Exception $e) {
            return ResponseHelper::error($e);
        }
    }

    public function availability($id, Request $request)
    {
        try {
            $request->validate([
                'reserved_at' => 'required|date',
                'duration' => 'required|integer|min:1'
            ]);
     
            $isAvailable = $this->resourceRepository->checkAvailability($id, $request->reserved_at, $request->duration);
            return ResponseHelper::success(["available" => !$isAvailable]);
        } catch (Exception $e) {
            return ResponseHelper::error($e);
        }
    }
}
