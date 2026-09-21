<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolingKitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tooling_kit_id',
        'item_id',
        'jumlah',
        'catatan',
    ];

    public function toolingKit()
    {
        return $this->belongsTo(ToolingKit::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
