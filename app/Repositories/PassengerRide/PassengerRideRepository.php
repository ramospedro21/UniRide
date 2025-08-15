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
}