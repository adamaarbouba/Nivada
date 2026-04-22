<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'hotel_id',
        'check_in_date',
        'check_out_date',
        'status',
        'payment_status',
        'total_amount',
        'special_requests',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'booking_items')
            ->withPivot('price_per_night', 'quantity')
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get total amount paid (payments only, not refunds)
     */
    public function amountPaid(): float
    {
        return $this->payments()
            ->where('type', 'payment')
            ->sum('amount');
    }

    /**
     * Get total refunded amount
     */
    public function amountRefunded(): float
    {
        return $this->payments()
            ->where('type', 'refund')
            ->sum('amount');
    }

    /**
     * Get net amount paid (paid minus refunded)
     */
    public function amountNetPaid(): float
    {
        return $this->amountPaid() - $this->amountRefunded();
    }

    /**
     * Get remaining balance
     */
    public function remainingBalance(): float
    {
        return $this->total_amount - $this->amountNetPaid();
    }
}
