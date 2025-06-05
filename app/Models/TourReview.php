<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourReview extends Model
{
    protected $table = 'reviews';
    protected $fillable = [
        'tour_id',
        'booking_id',
        'rating',
        'comment',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
} 