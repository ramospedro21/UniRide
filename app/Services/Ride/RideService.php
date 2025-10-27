<?php

namespace App\Services\Ride;

use App\Repositories\Ride\RideRepository;
use App\Services\RideMatching\RideMatchingService;
use Carbon\Carbon;

class RideService
{
    protected $rideRepository;
    protected $matchingService;

    public function __construct(RideRepository $rideRepository, RideMatchingService $matchingService) {
        $this->rideRepository = $rideRepository;
        $this->matchingService = $matchingService;
    }

    public function all(array $data = [])
    {
        $rides = $this->rideRepository->searchNearby($data);
        $requestedDays = $data['days'] ?? [];

        // Aplica heurística e filtra rides sem coincidência de dias
        $scored = $rides->filter(function ($ride) use ($requestedDays) {
            $rideDays = $ride->weekDays->pluck('day_of_week')->toArray();
            return count(array_intersect($requestedDays, $rideDays)) > 0;
        })->map(function ($ride) use ($data) {

            $score = $this->matchingService->calculateScore($ride, $data);

            return [
                'id' => $ride->id,
                'driver' => [
                    'id' => $ride->driver->id,
                    'name' => $ride->driver->name,
                    'rating' => round($ride->driver->averageRating(), 2),
                ],
                'car' => $ride->car,
                'departure_time' => $ride->departure_time,
                'capacity' => $ride->capacity,
                'ride_fare' => $ride->ride_fare,
                'passengers_count' => $ride->passengers_count,
                'available_seats' => $ride->available_seats,
                'departure_distance' => $ride->departure_distance,
                'week_days' => $ride->week_days_translated,
                'departure_address' => $ride->departure_address,
                'arrival_address' => $ride->arrival_address,
                'short_departure_address' => $ride->short_departure_address,
                'short_arrival_address' => $ride->short_arrival_address,
                'matching_score' => $score,
            ];
        });

        return $scored->sortByDesc('matching_score')->values();
    }

    public function create(array $data)
    {
        $data['arrival_address'] = "UNIP - Rod. Pres. Dutra, km 157 - 5 - Limoeiro, São José dos Campos - SP, 12240-420";

        $ride = $this->rideRepository->create($data);
        $this->rideRepository->saveWeekDays($ride, $data['week_days']);

        return $ride;
    }

    public function show($user_id, $id)
    {
        return $this->rideRepository->find($id, $user_id);
    }

    public function update($id, array $data, $user_id)
    {
        $ride = $this->rideRepository->find($id, $user_id);
        return $this->rideRepository->update($ride, $data);
    }

    public function delete($id, $user_id)
    {
        $ride = $this->rideRepository->find($id, $user_id);
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