<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Ride\RideService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RideController extends Controller
{
    protected $rideService;

    public function __construct(RideService $rideService) {
        $this->rideService = $rideService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $rides = $this->rideService->all($data);

        return $this->respondWithOk($rides);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'driver_id' => 'required|integer|exists:users,id',
                'car_id' => 'required|integer|exists:cars,id',
                'departure_location_lat' => 'required|string',
                'departure_location_long' => 'required|string',
                'arrive_location_lat' => 'required|string',
                'arrive_location_long' => 'required|string',
                'departure_time' => 'required|date_format:H:i',
                'capacity' => 'required|integer|min:1',
                'ride_fare' => 'required|numeric|min:0',
                'week_days' => 'required|array|min:1',
                'week_days.*' => 'integer|between:0,6',
                'departure_address' => 'string',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();
    
            $this->rideService->create($data);
    
            return $this->respondWithOk();

        } catch (ValidationException $e) {

            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {

            return $this->respondWithErrors($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        $ride = $this->rideService->show($request->user()->id, $id);

        return $this->respondWithOk($ride);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $this->rideService->update($id, $request->toArray(), $request->user()->id);

        return $this->respondWithOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id, Request $request)
    {
        $this->rideService->delete($id, $request->user()->id);

        return $this->respondWithOk();
    }

    public function getRidesByUser($user_id)
    {
        try {
            $rides = $this->rideService->getByUser($user_id);
            return $this->respondWithOk($rides);
        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    public function getRidesWithPendingRequests($driverId)
    {
        try {
            $rides = $this->rideService->getPendingRidesForDriver($driverId);
            return $this->respondWithOk($rides);
        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}
