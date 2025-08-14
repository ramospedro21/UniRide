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

    public function searchNearby(array $data)
    {
        $departureLat = $data['departure_lat'] ?? null;
        $departureLng = $data['departure_long'] ?? null;
        $arrivalLat = $data['arrival_lat'] ?? null;
        $arrivalLng = $data['arrival_long'] ?? null;

        $maxDistanceKm = 10;

        return Ride::select('*')
            ->whereRaw('
                (6371 * acos(
                    cos(radians(?)) * cos(radians(departure_location_lat)) *
                    cos(radians(departure_location_long) - radians(?)) +
                    sin(radians(?)) * sin(radians(departure_location_lat))
                )) < ?
            ', [$departureLat, $departureLng, $departureLat, $maxDistanceKm])
            ->whereRaw('
                (6371 * acos(
                    cos(radians(?)) * cos(radians(arrive_location_lat)) *
                    cos(radians(arrive_location_long) - radians(?)) +
                    sin(radians(?)) * sin(radians(arrive_location_lat))
                )) < ?
            ', [$arrivalLat, $arrivalLng, $arrivalLat, $maxDistanceKm])
            ->whereRaw('
                capacity > (
                    SELECT COUNT(*) FROM passenger_rides
                    WHERE passenger_rides.ride_id = rides.id
                )
            ')
            ->orderBy('departure_time')
            ->get();
    }
}