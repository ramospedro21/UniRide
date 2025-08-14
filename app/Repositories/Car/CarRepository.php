<?php

namespace App\Repositories\Car;

Use App\Models\Car;

class CarRepository 
{
    public function all()
    {
        return Car::get();
    }

    public function find($id)
    {
        return Car::find($id);
    }

    public function create(array $data)
    {
        return Car::create($data);
    }

    public function update(Car $car, array $data)
    {
        return $car->update($data);
    }

    public function delete(Car $car)
    {
        return $car->delete();
    }

    public function getCarsByUser($userId)
    {
        return Car::where('driver_id', $userId)->get();
    }
}