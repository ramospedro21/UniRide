<?php

namespace App\Repositories\Ride;

Use App\Models\Ride;
use App\Models\RideWeekDay;

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

    public function saveWeekDays(Ride $ride, array $weekDays)
    {
        $rideWeekDays = [];
        foreach ($weekDays as $day) {
            $rideWeekDays[] = [
                'ride_id' => $ride->id,
                'day_of_week' => $day,
            ];
        }
        return $ride->weekDays()->createMany($rideWeekDays);
    }

    public function searchNearby(array $data)
    {
        $departureLat = $data['departure_lat'] ?? null;
        $departureLng = $data['departure_long'] ?? null;
        $arrivalLat = $data['arrival_lat'] ?? null;
        $arrivalLng = $data['arrival_long'] ?? null;

        $maxDistanceKm = 10;

        return Ride::with(['driver', 'car', 'weekDays'])
                ->leftJoin('passenger_rides', 'rides.id', '=', 'passenger_rides.ride_id')
                ->select('rides.*')
                ->selectRaw('
                    (COUNT(passenger_rides.id)) AS passengers_count
                ')
                ->selectRaw('
                    (capacity - COUNT(passenger_rides.id)) AS available_seats
                ')
                ->selectRaw('
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(departure_location_lat)) *
                        cos(radians(departure_location_long) - radians(?)) +
                        sin(radians(?)) * sin(radians(departure_location_lat))
                    )) AS departure_distance
                ', [$departureLat, $departureLng, $departureLat])
                ->selectRaw('
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(arrive_location_lat)) *
                        cos(radians(arrive_location_long) - radians(?)) +
                        sin(radians(?)) * sin(radians(arrive_location_lat))
                    )) AS arrival_distance
                ', [$arrivalLat, $arrivalLng, $arrivalLat])
                ->groupBy('rides.id')
                ->havingRaw('available_seats > 0')
                ->havingRaw('departure_distance < ?', [$maxDistanceKm])
                ->havingRaw('arrival_distance < ?', [$maxDistanceKm])
                ->orderByRaw('(departure_distance + arrival_distance) ASC')
                ->get()
                ->map(function ($ride) {
                    return [
                        'id' => $ride->id,
                        'driver' => $ride->driver,
                        'car' => $ride->car,
                        'departure_time' => $ride->departure_time,
                        'capacity' => $ride->capacity,
                        'ride_fare' => $ride->ride_fare,
                        'passengers_count' => $ride->passengers_count,
                        'available_seats' => $ride->available_seats,
                        'departure_distance' => $ride->departure_distance,
                        'arrival_distance' => $ride->arrival_distance,
                        'week_days' => $ride->week_days_translated,
                        'departure_address' => $ride->departure_address,
                        'arrival_address' => $ride->arrival_address,
                    ];
                });

    }
}