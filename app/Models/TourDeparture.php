<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourDeparture extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'guide_id',
        'departure_date',
        'status'
    ];

    protected $casts = [
        'departure_date' => 'datetime',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tour_departure_id');
    }
}
