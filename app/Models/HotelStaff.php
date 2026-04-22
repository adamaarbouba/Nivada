<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelStaff extends Model
{
    protected $table = 'hotel_staff';

    protected $fillable = [
        'user_id',
        'hotel_id',
        'role', // cleaner or inspector
        'hourly_rate',
        'is_available',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'is_available' => 'boolean',
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
}
