<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerRide extends Model
{
    const STATUS = [
        'PENDING' => 0,
        'ACCEPTED' => 1,
        'CANCELLED' => 2,   
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
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
