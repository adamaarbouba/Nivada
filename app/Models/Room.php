<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_number',
        'room_type',
        'floor',
        'capacity',
        'price_per_night',
        'status',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];

    // Relationships
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function cleaningLogs(): HasMany
    {
        return $this->hasMany(CleaningLog::class);
    }

    public function inspectionRequests(): HasMany
    {
        return $this->hasMany(InspectionRequest::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_items')
            ->withPivot('price_per_night', 'quantity')
            ->withTimestamps();
    }
}
