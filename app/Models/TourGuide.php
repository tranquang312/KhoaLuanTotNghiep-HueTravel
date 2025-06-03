<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'booking_id',
        'status',
        'note',
        'assigned_at',
        'confirmed_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime'
    ];

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
