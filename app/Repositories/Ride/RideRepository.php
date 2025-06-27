<?php

namespace App\Repositories\Ride;

Use App\Models\Ride;

class RideRepository 
{
    public function all()
    {
        return Ride::get();
    }

    public function find($id)
    {
        return Ride::find($id);
    }

    public function create(array $data)
    {
        return Ride::create($data);
    }

    public function update(Ride $ride, array $data)
    {
        return $ride->update($data);
    }

    public function delete(Ride $ride)
    {
        return $ride->delete();
    }
}