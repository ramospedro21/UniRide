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
        'departure_address',
        'arrive_location_lat',
        'arrive_location_long',
        'arrival_address',
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

    public function weekDays()
    {
        return $this->hasMany(RideWeekDay::class);
    }

    public function getWeekDaysTranslatedAttribute()
    {
        $days = [
            0 => 'Segunda-feira',
            1 => 'Terça-feira',
            2 => 'Quarta-feira',
            3 => 'Quinta-feira',
            4 => 'Sexta-feira',
            5 => 'Sábado',
            6 => 'Domingo',
        ];

        return $this->weekDays->map(fn($wd) => $days[$wd->day_of_week] ?? null);
    }
}
