<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerRide extends Model
{
    const STATUS = [
        'PENDING' => 0,
        'ACCEPTED' => 1,
        'COMPLETED' => 2,
        'CANCELLED' => 3,   
    ];

    protected $table = 'passenger_rides';

    protected $fillable = [
        'user_id',
        'ride_id',
        'evaluation',
        'status',
        'created_at',
        'updated_at',
    ];

    public function passenger()
    {
        return $this->belongsTo(User::class);
    }

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
