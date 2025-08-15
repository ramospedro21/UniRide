<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'driver_id',
        'car_id',
        'departure_location_lat',
        'departure_location_long',
        'arrive_location_lat',
        'arrive_location_long',
        'departure_time',
        'capacity',
        'ride_fare'
    ];

    public function passengerRides()
    {
        return $this->hasMany(PassengerRide::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
