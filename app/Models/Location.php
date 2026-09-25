<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kode',
        'deskripsi',
        'parent_id',
        'status',
    ];

    /**
     * Lokasi induk (misal: Ruangan)
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Sub-lokasi (misal: Rak di dalam Ruangan)
     */
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Barang yang berada di lokasi ini
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Hanya lokasi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Hanya lokasi level teratas (tidak punya parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Label lengkap termasuk parent (misal: "Gudang A › Rak 1")
     */
    public function getFullLabelAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->name . ' › ' . $this->name;
        }
        return $this->name;
    }

    public static function generateKode(string $name, ?int $ignoreId = null): string
    {
        $name = trim($name);
        if ($name === '') {
            $count = static::count() + 1;
            return 'LOK-' . str_pad((string)$count, 2, '0', STR_PAD_LEFT);
        }

        $words = preg_split('/\s+/', $name);
        $words = array_values(array_filter($words, fn($w) => strlen($w) > 0));

        if (count($words) === 1) {
            $w = strtoupper($words[0]);
            preg_match('/\d+$/', $w, $digitMatches);
            $digits = $digitMatches[0] ?? '';
            $letters = preg_replace('/\d+$/', '', $w);

            $vowels = ['A', 'E', 'I', 'O', 'U'];
            $picked = '';
            for ($i = 0; $i < strlen($letters) && strlen($picked) < 3; $i++) {
                if ($i === 0 || !in_array($letters[$i], $vowels)) {
                    $picked .= $letters[$i];
                }
            }
            if (strlen($picked) < 2) {
                $picked = substr($letters, 0, 3);
            }
            $base = substr($picked . $digits, 0, 6);
        } else {
            $base = '';
            for ($i = 0; $i < count($words); $i++) {
                $w = strtoupper($words[$i]);
                if ($i < count($words) - 1) {
                    $base .= $w[0];
                } else {
                    preg_match('/\d+$/', $w, $digitMatches);
                    $digits = $digitMatches[0] ?? '';
                    $base .= $w[0] . $digits;
                }
            }
            $base = substr($base, 0, 6);
        }

        if (empty($base)) {
            $base = 'RAK';
        }

        $candidate = $base;
        $counter = 1;
        while (static::where('kode', $candidate)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $counter++;
            $candidate = $base . $counter;
            if (strlen($candidate) > 20) {
                $candidate = substr($base, 0, 15) . $counter;
            }
        }

        return $candidate;
    }
}

