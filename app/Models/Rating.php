<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'ride_id',
        'reviewer_id',
        'reviewed_id',
        'score',
        'comment',
    ];

    public function reviewer() {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewed() {
        return $this->belongsTo(User::class, 'reviewed_id');
    }

    public function ride() {
        return $this->belongsTo(Ride::class);
    }
}

