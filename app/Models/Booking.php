<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'tour_id',
        'name',
        'email',
        'user_id',
        'tour_departure_id',
        'phone',
        'people',
        'children',
        'start_date',
        'note',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function tourDeparture()
    {
        return $this->belongsTo(TourDeparture::class);
    }

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function getTotalBooking()
    {
        return ($this->people + $this->children * 0.7) * $this->tour->price;
    }
}
