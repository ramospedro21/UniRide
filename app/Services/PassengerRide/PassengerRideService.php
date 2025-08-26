<?php

namespace App\Services\PassengerRide;

use App\Repositories\PassengerRide\PassengerRideRepository;

class PassengerRideService
{
    protected $passengerRideRepository;

    public function __construct(PassengerRideRepository $passengerRideRepository) {
        $this->passengerRideRepository = $passengerRideRepository;
    }

    public function all(array $data = [])
    {
        return $this->passengerRideRepository->all($data);
    }

    public function create(array $data)
    {
        return $this->passengerRideRepository->create($data);
    }

    public function show($id)
    {
        return $this->passengerRideRepository->find($id);
    }

    public function update($id, array $data)
    {
        $ride = $this->passengerRideRepository->find($id);
        return $this->passengerRideRepository->update($ride, $data);
    }

    public function delete($id)
    {
        $ride = $this->passengerRideRepository->find($id);
        return $this->passengerRideRepository->delete($ride);
    }

    public function handleReservation($id, $action)
    {
        $passengerRide = $this->passengerRideRepository->find($id);
        return $this->passengerRideRepository->handleReservation($passengerRide, $action);
    }

}