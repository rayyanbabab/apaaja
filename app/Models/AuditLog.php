<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'System']);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /** Human-friendly label for each action code */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'item.created'        => 'Tambah Barang',
            'item.updated'        => 'Edit Barang',
            'item.deleted'        => 'Hapus Barang',
            'incoming.created'    => 'Barang Masuk',
            'incoming.deleted'    => 'Hapus Masuk',
            'outgoing.created'    => 'Barang Keluar',
            'outgoing.deleted'    => 'Hapus Keluar',
            'borrowing.approved'  => 'Setujui Peminjaman',
            'borrowing.rejected'  => 'Tolak Peminjaman',
            'borrowing.completed' => 'Selesai Peminjaman',
            'user.created'        => 'Tambah User',
            'user.updated'        => 'Edit User',
            'user.deleted'        => 'Hapus User',
            'user.status_changed' => 'Status User',
            'supplier.created'    => 'Tambah Supplier',
            'supplier.updated'    => 'Edit Supplier',
            'supplier.deleted'    => 'Hapus Supplier',
            'category.created'    => 'Tambah Kategori',
            'category.updated'    => 'Edit Kategori',
            'category.deleted'    => 'Hapus Kategori',
            default               => ucfirst(str_replace('.', ' ', $this->action)),
        };
    }

    /** Bootstrap color for each module badge */
    public function getModuleColorAttribute(): string
    {
        return match ($this->module) {
            'Inventory'   => 'blue',
            'Barang Masuk'=> 'green',
            'Barang Keluar'=> 'orange',
            'Peminjaman'  => 'purple',
            'User'        => 'cyan',
            'Supplier'    => 'yellow',
            'Kategori'    => 'pink',
            default       => 'gray',
        };
    }
}
