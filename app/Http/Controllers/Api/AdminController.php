<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TripsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponseTrait;
    protected $TripService;
    public function __construct(TripsService $TripService)
    {
        $this->TripService=$TripService;
    }

    public function trips()
    {

        $trips = $this->TripService->allTrips();
        return $this->apiResponse($trips,"Get All Trips Successfully",200);
    }
}
