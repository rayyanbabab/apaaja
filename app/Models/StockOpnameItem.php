<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameItem extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'item_id',
        'system_stok',
        'actual_stok',
        'variance',
        'notes',
    ];

    protected $casts = [
        'system_stok' => 'integer',
        'actual_stok' => 'integer',
        'variance' => 'integer',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
