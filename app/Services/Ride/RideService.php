<?php

namespace App\Services\Ride;

use App\Repositories\Ride\RideRepository;

class RideService
{
    protected $rideRepository;

    public function __construct(RideRepository $rideRepository) {
        $this->rideRepository = $rideRepository;
    }

    public function all()
    {
        return $this->rideRepository->all();
    }

    public function create(array $data)
    {
        return $this->rideRepository->create($data);
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