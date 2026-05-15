<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_barang',
        'jumlah',
        'alasan',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'item_id',
        'inventory_id',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'jumlah'      => 'integer',
    ];

    /* ── Relationships ── */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /* ── Scopes ── */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
