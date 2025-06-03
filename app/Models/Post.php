<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'title',
        'content',
        'images',
        'likes_count',
        'is_approved'
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
        'likes_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
