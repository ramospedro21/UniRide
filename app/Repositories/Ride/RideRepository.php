<?php

namespace App\Repositories\Ride;

use App\Models\PassengerRide;
Use App\Models\Ride;
use App\Models\RideWeekDay;
use Mockery\Generator\StringManipulation\Pass\Pass;

class RideRepository 
{
    public function all()
    {
        return Ride::get();
    }

    public function find($id, $userId)
    {
        $ride = Ride::with([
            'car',
            'reviews',
            'driver',
            'passengerRides.passenger.receivedRatings' => function($query) use ($id) {
                $query->where('ride_id', $id);
            }
        ])->find($id);

        $ride->authUserReview = $ride->reviews
            ->firstWhere('reviewer_id', $userId);

        return $ride;
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
        $maxDistanceKm = 5;

        return Ride::with(['driver', 'car', 'weekDays'])
            ->leftJoin('passenger_rides', 'rides.id', '=', 'passenger_rides.ride_id')
            ->select('rides.*')
            ->selectRaw('COUNT(passenger_rides.id) AS passengers_count')
            ->selectRaw('(capacity - COUNT(passenger_rides.id)) AS available_seats')
            ->selectRaw('(
                6371 * acos(
                    cos(radians(?)) * cos(radians(departure_location_lat)) *
                    cos(radians(departure_location_long) - radians(?)) +
                    sin(radians(?)) * sin(radians(departure_location_lat))
                )
            ) AS departure_distance', [$departureLat, $departureLng, $departureLat])
            ->selectRaw('(
                6371 * acos(
                    cos(radians(?)) * cos(radians(arrive_location_lat)) *
                    cos(radians(arrive_location_long) - radians(?)) +
                    sin(radians(?)) * sin(radians(arrive_location_lat))
                )
            ) AS arrival_distance', [$arrivalLat, $arrivalLng, $arrivalLat])
            ->groupBy('rides.id')
            ->havingRaw('available_seats > 0')
            ->havingRaw('departure_distance < ?', [$maxDistanceKm])
            ->havingRaw('arrival_distance < ?', [$maxDistanceKm])
            ->get();
    }


    public function getRequestedByUser(int $userId)
    {
        return PassengerRide::with([
                    'ride.reviews' => function ($query) use ($userId) {
                        $query->where('reviewer_id', $userId);
                    }
                ])
                ->where('user_id', $userId)
                ->get();

    }

    public function getOfferedByUser(int $userId)
    {
        return Ride::with('passengerRides')
            ->where('driver_id', $userId)
            ->get();
    }

    public function getPendingRidesForDriver(int $driverId)
    {
        return Ride::with(['passengerRides' => function($query) {
                $query->where('status', PassengerRide::STATUS['PENDING'])->with('passenger');
            }])
            ->where('driver_id', $driverId)
            ->whereHas('passengerRides', function($query) {
                $query->where('status', PassengerRide::STATUS['PENDING']);
            })
            ->get();
    }
}