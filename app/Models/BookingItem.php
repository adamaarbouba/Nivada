<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingItem extends Model
{
    protected $table = 'booking_items';

    protected $fillable = [
        'booking_id',
        'room_id',
        'price_per_night',
        'quantity',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
