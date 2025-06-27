<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Ride\RideService;
use Illuminate\Http\Request;

class RideController extends Controller
{
    protected $rideService;

    public function __construct(RideService $rideService) {
        $this->rideService = $rideService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rides = $this->rideService->all();

        return $this->respondWithOk($rides);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'driver_id' => 'required|integer',
                'car_id' => 'required|integer',
                'departure_location_lat' => 'required|string',
                'departure_location_long' => 'required|string',
                'arrive_location_lat' => 'required|string',
                'arrive_location_long' => 'required|string',
                'departure_time' => 'required|date_format:H:i',
                'capacity' => 'required|integer',
                'ride_fare' => 'required|decimal:0,8',
            ]);
    
            $this->rideService->create($data);
    
            return $this->respondWithOk();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $ride = $this->rideService->show($id);

        return $this->respondWithOk($ride);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $this->rideService->update($id, $request->toArray());

        return $this->respondWithOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $this->rideService->delete($id);

        return $this->respondWithOk();
    }
}
