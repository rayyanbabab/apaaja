<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolingKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'target_machine',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kitItems()
    {
        return $this->hasMany(ToolingKitItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'tooling_kit_items')
                    ->withPivot(['jumlah', 'catatan'])
                    ->withTimestamps();
    }
}
