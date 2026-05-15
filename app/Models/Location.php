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
}
