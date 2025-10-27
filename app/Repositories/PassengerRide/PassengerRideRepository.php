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

    public function delete($ride, $userId)
    {
        $ride = PassengerRide::where('id', $ride)
                             ->where('user_id', $userId)
                             ->first();
        $ride->status = PassengerRide::STATUS['CANCELLED'];

        return $ride->save();
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