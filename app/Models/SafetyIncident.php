<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'borrowing_request_id',
        'incident_type',
        'incident_date',
        'location',
        'description',
        'action_taken',
        'penalty_days',
        'reported_by',
        'status',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'penalty_days'  => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function borrowingRequest()
    {
        return $this->belongsTo(BorrowingRequest::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function getIncidentTypeBadgeAttribute(): array
    {
        return match ($this->incident_type) {
            'minor_injury' => ['label' => 'Cedera Ringan (K3)', 'color' => 'red'],
            'near_miss'    => ['label' => 'Near-Miss (Hampir Celaka)', 'color' => 'amber'],
            'apd_violation'=> ['label' => 'Pelanggaran APD', 'color' => 'orange'],
            'sop_violation'=> ['label' => 'Pelanggaran SOP Alat', 'color' => 'purple'],
            'tool_misuse'  => ['label' => 'Salah Prosedur Alat', 'color' => 'rose'],
            default        => ['label' => ucfirst($this->incident_type), 'color' => 'slate'],
        };
    }
}
