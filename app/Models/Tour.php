<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'itinerary',
    ];

    public function destinations()
    {
        return $this->belongsToMany(Destination::class);
    }
    public function images()
    {
        return $this->hasMany(TourImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function guideBooking()
    {
        return $this->hasOne(GuideBooking::class);
    }

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
}
