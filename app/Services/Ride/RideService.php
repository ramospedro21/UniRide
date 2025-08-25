<?php

namespace App\Services\Ride;

use App\Repositories\Ride\RideRepository;

class RideService
{
    protected $rideRepository;

    public function __construct(RideRepository $rideRepository) {
        $this->rideRepository = $rideRepository;
    }

    public function all(array $data = [])
    {
        return $this->rideRepository->searchNearby($data);
    }

    public function create(array $data)
    {
        $data['arrival_address'] = "UNIP - Rod. Pres. Dutra, km 157 - 5 - Limoeiro, São José dos Campos - SP, 12240-420";

        $ride = $this->rideRepository->create($data);
        $this->rideRepository->saveWeekDays($ride, $data['week_days']);

        return $ride;
    }

    public function show($id)
    {
        return $this->rideRepository->find($id);
    }

    public function update($id, array $data)
    {
        $ride = $this->rideRepository->find($id);
        return $this->rideRepository->update($ride, $data);
    }

    public function delete($id)
    {
        $ride = $this->rideRepository->find($id);
        return $this->rideRepository->delete($ride);
    }

    public function getByUser($id)
    {
        $requested = $this->rideRepository->getRequestedByUser($id);
        $offered  = $this->rideRepository->getOfferedByUser($id);

        return [
            'requested' => $requested,
            'offered'  => $offered,
        ];
    }

    public function getPendingRidesForDriver(int $driverId)
    {
        $rides =  $this->rideRepository->getPendingRidesForDriver($driverId);

        return $rides->map(function($ride) {
            return [
                'id' => $ride->id,
                'departure_time' => $ride->departure_time,
                'arrival_time' => $ride->arrival_time,
                'departure_address' => $ride->departure_address,
                'capacity' => $ride->capacity,
                'ride_fare' => $ride->ride_fare,
                'pending_requests_count' => $ride->passengerRides->count(),
                'pending_requests' => $ride->passengerRides->map(function($pr) {
                    return [
                        'passenger_ride_id' => $pr->id,
                        'user_id' => $pr->user_id,
                        'status' => $pr->status === 0 ? 'pendente' : 'confirmada',
                        'created_at' => $pr->created_at,
                        'passenger' => [
                            'id' => $pr->passenger->id,
                            'name' => $pr->passenger->name,
                            'email' => $pr->passenger->email,
                        ]
                    ];
                }),
            ];
        });
    }

}