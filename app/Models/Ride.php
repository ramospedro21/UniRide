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

    protected $appends = ['week_days_translated', 'short_departure_address', 'short_arrival_address'];

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

    public function reviews()
    {
        return $this->hasMany(Rating::class, 'ride_id');
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
    
    public function getShortDepartureAddressAttribute()
    {
        return $this->formatDepartureAddress($this->departure_address);
    }

    public function getShortArrivalAddressAttribute()
    {
        return $this->formatArrivalAddress($this->arrival_address);
    }

    private function formatDepartureAddress(?string $address): ?string
    {
        if (!$address) return null;

        $parts = explode(',', $address);

        // Rua + número (primeira parte)
        $streetAndNumber = trim($parts[0] ?? '');

        // Cidade (sempre antes do "- SP" ou "- São Paulo")
        $city = '';
        foreach ($parts as $part) {
            if (stripos($part, 'São José dos Campos') !== false) {
                $city = 'São José dos Campos';
                break;
            }
        }

        return "{$streetAndNumber}, {$city}";
    }

    private function formatArrivalAddress(?string $address): ?string
    {
        if (!$address) return null;

        // Pega a primeira parte (ex: "UNIP - Rod. Pres. Dutra")
        $parts = explode(',', $address);
        $place = trim($parts[0] ?? '');

        // Cidade (vai estar em alguma parte com "São José dos Campos")
        $city = '';
        foreach ($parts as $part) {
            if (stripos($part, 'São José dos Campos') !== false) {
                $city = 'São José dos Campos';
                break;
            }
        }

        return "{$place}, {$city}";
    }
}
