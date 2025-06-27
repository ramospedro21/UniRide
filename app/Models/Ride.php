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
}
