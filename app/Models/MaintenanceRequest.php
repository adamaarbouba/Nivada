<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRequest extends Model
{
    protected $table = 'maintenance_requests';

    protected $fillable = [
        'room_id',
        'hotel_id',
        'created_by_inspector_id',
        'issue_description',
        'priority', // urgent, normal, low
        'status', // pending, in-progress, completed
        'completion_notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_inspector_id');
    }
}
