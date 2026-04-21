<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CleaningLog extends Model
{
    protected $table = 'cleaning_logs';

    protected $fillable = [
        'room_id',
        'user_id',
        'type', // cleaning or inspection
        'message',
        'notes',
        'action', // finished or re-clean
    ];

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inspectionRequests(): HasMany
    {
        return $this->hasMany(InspectionRequest::class);
    }
}
