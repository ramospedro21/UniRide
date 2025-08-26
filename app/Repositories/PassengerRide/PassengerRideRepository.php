<?php

namespace App\Repositories\PassengerRide;

Use App\Models\PassengerRide;

class PassengerRideRepository 
{
    public function all()
    {
        return PassengerRide::get();
    }

    public function find($id)
    {
        return PassengerRide::find($id);
    }

    public function create(array $data)
    {
        return PassengerRide::create($data);
    }

    public function update(PassengerRide $ride, array $data)
    {
        return $ride->update($data);
    }

    public function delete(PassengerRide $ride)
    {
        return $ride->delete();
    }

    public function handleReservation(PassengerRide $passengerRide, $action)
    {
        if ($action === 'approve') {
            $passengerRide->status = PassengerRide::STATUS['ACCEPTED'];
        } elseif ($action === 'reject') {
            $passengerRide->status = PassengerRide::STATUS['CANCELLED'];
        } else {
            throw new \InvalidArgumentException("Ação inválida. Use 'accept' ou 'reject'.");
        }

        $passengerRide->save();
        return $passengerRide;
    }
}