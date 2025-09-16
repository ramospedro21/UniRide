<?php

namespace App\Repositories\Ratings;

Use App\Models\Rating;

class RatingsRepository 
{
    public function all()
    {
        return Rating::get();
    }

    public function find($id)
    {
        return Rating::find($id);
    }

    public function create(array $data)
    {
        return Rating::create($data);
    }

    public function update(Rating $car, array $data)
    {
        return $car->update($data);
    }

    public function delete(Rating $car)
    {
        return $car->delete();
    }
}