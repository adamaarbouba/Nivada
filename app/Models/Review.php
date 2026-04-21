<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'hotel_id',
        'room_id',
        'rating', // 1-5
        'cleanliness_rating', // 1-5
        'service_rating', // 1-5
        'amenity_rating', // 1-5
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
        'cleanliness_rating' => 'integer',
        'service_rating' => 'integer',
        'amenity_rating' => 'integer',
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
