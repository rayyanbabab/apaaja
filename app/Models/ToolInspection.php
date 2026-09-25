<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolInspection extends Model
{
    protected $fillable = [
        'borrowing_request_id', 'item_id', 'inspector_id',
        'image_path', 'image_original_name',
        'verdict', 'defect_types', 'wear_percentage',
        'defect_confidence', 'defect_regions', 'scan_metrics',
        'inspection_stage', 'notes', 'scan_duration_ms',
        'bak_issued', 'bak_number', 'bak_issued_at',
        'maintenance_triggered', 'maintenance_id',
    ];

    protected $casts = [
        'defect_types'   => 'array',
        'defect_regions' => 'array',
        'scan_metrics'   => 'array',
        'bak_issued'     => 'boolean',
        'bak_issued_at'  => 'datetime',
        'maintenance_triggered' => 'boolean',
    ];

    /* ── Relationships ─────────────────────────────── */

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function borrowingRequest(): BelongsTo
    {
        return $this->belongsTo(BorrowingRequest::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class);
    }

    /* ── Verdict Helpers ───────────────────────────── */

    public function getVerdictBadgeAttribute(): array
    {
        return match($this->verdict) {
            'ok'         => ['label' => 'Kondisi Baik',    'class' => 'bg-emerald-100 text-emerald-800', 'dot' => 'bg-emerald-500', 'icon' => '✓'],
            'minor_wear' => ['label' => 'Aus Ringan',      'class' => 'bg-yellow-100 text-yellow-800',   'dot' => 'bg-yellow-500',  'icon' => '⚠'],
            'damaged'    => ['label' => 'Rusak / Cacat',   'class' => 'bg-orange-100 text-orange-800',   'dot' => 'bg-orange-500',  'icon' => '!'],
            'critical'   => ['label' => 'Kritis',          'class' => 'bg-red-100 text-red-800',         'dot' => 'bg-red-500',     'icon' => '✕'],
            default      => ['label' => 'Tidak Diketahui', 'class' => 'bg-gray-100 text-gray-700',       'dot' => 'bg-gray-400',    'icon' => '?'],
        };
    }

    public function isDefective(): bool
    {
        return in_array($this->verdict, ['damaged', 'critical']);
    }

    public function needsMaintenance(): bool
    {
        return in_array($this->verdict, ['minor_wear', 'damaged', 'critical']);
    }

    public function getDefectTypesLabelAttribute(): string
    {
        $labels = [
            'chip'       => 'Gompal/Chipping',
            'crack'      => 'Retak/Crack',
            'corrosion'  => 'Korosi',
            'wear'       => 'Keausan Normal',
            'deformation'=> 'Deformasi Plastis',
            'scratch'    => 'Goresan Berat',
        ];
        $types = $this->defect_types ?? [];
        return implode(', ', array_map(fn($t) => $labels[$t] ?? $t, $types));
    }

    /* ── BAK Number Generator ──────────────────────── */

    public static function generateBakNumber(): string
    {
        $year  = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->where('bak_issued', true)->count() + 1;
        return sprintf('BAK/%s/%s/%04d', $year, $month, $count);
    }
}
