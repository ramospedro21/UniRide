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

}