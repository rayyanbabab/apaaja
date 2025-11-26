<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'batch_id',
        'item_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'keterangan',
        'kondisi_pinjam',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

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

    public function scopeBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function batchRequests()
    {
        return $this->hasMany(BorrowingRequest::class, 'batch_id', 'batch_id')
            ->where('id', '!=', $this->id);
    }
}
