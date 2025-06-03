<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'short_description',
        'description',
    ];

    public function images()
    {
        return $this->hasMany(DestinationImage::class);
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class);
    }
}
