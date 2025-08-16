<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideWeekDay extends Model
{
    protected $table = 'ride_week_days';

    protected $fillable = [
        'ride_id',
        'day_of_week',
    ];

    /**
     * Get the ride that owns the week day.
     */
    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
