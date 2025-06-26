<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = [
        'driver_id',
        'brand',
        'model',
        'color',
        'plate',
        'is_default_veichle'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
