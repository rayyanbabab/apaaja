<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    public const STATUS_IN_REPAIR  = 'in_repair';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_SCRAPPED   = 'scrapped';

    protected $fillable = [
        'item_id',
        'user_id',
        'jumlah',
        'status',
        'kondisi_masuk',
        'catatan',
        'catatan_selesai',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'jumlah'       => 'integer',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    /* ──────────────────────────── Relations ──────────────────────────── */

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ──────────────────────────── Scopes ──────────────────────────── */

    public function scopeInRepair(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_IN_REPAIR);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeScrapped(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCRAPPED);
    }

    /* ──────────────────────────── Helpers ──────────────────────────── */

    /**
     * Label teks untuk status.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_IN_REPAIR => 'Sedang Diservis',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_SCRAPPED  => 'Dihapuskan (Scrap)',
            default                => ucfirst($this->status),
        };
    }

    /**
     * Warna Tailwind badge untuk status.
     */
    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_IN_REPAIR => 'bg-orange-100 text-orange-700',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-700',
            self::STATUS_SCRAPPED  => 'bg-red-100 text-red-700',
            default                => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Tandai maintenance selesai & kembalikan stok ke item.
     */
    public function selesai(?string $catatanSelesai = null): bool
    {
        if ($this->status !== self::STATUS_IN_REPAIR) {
            return false;
        }

        // Kembalikan stok
        $item = $this->item;
        if ($item) {
            $item->addStok($this->jumlah, $item->type?->value === 'peminjaman' ? 'peminjaman' : 'reguler');
        }

        return $this->update([
            'status'          => self::STATUS_COMPLETED,
            'catatan_selesai' => $catatanSelesai,
            'completed_at'    => now(),
        ]);
    }

    /**
     * Tandai scrap — stok TIDAK dikembalikan.
     */
    public function scrap(?string $catatanSelesai = null): bool
    {
        if ($this->status !== self::STATUS_IN_REPAIR) {
            return false;
        }

        return $this->update([
            'status'          => self::STATUS_SCRAPPED,
            'catatan_selesai' => $catatanSelesai,
            'completed_at'    => now(),
        ]);
    }
}
