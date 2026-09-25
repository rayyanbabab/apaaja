<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'keterangan',
        'kondisi_pinjam',
        'kondisi_kembali',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'jumlah' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'dipinjam' &&
               $this->tanggal_kembali_rencana !== null &&
               $this->tanggal_kembali_rencana->lt(today());
    }

    public function getDaysOverdue(): int
    {
        if (! $this->isOverdue() || ! $this->tanggal_kembali_rencana) {
            return 0;
        }

        return (int) ceil($this->tanggal_kembali_rencana->diffInDays(today(), true));
    }
}
