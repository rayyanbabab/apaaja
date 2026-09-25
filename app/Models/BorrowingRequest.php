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
        'overdue_notified_at',
        // Tahap 4 – Digital Signature BAP
        'bap_token',
        'signature_data',
        'signed_by_name',
        'signed_at',
        'bap_number',
        // K3 Safety Interlock & Physical Verification
        'safety_agreed_at',
        'safety_apd_checklist',
        'safety_verified_by',
        'safety_verified_at',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_kembali_rencana' => 'date',
        'approved_at'             => 'datetime',
        'completed_at'            => 'datetime',
        'overdue_notified_at'     => 'datetime',
        'signed_at'               => 'datetime',
        'safety_agreed_at'        => 'datetime',
        'safety_apd_checklist'    => 'array',
        'safety_verified_at'      => 'datetime',
    ];

    /** Returns true if the BAP has been digitally signed. */
    public function isSigned(): bool
    {
        return ! is_null($this->signed_at);
    }

    /** Returns true if safety induction/agreement is completed */
    public function isSafetyInductionCompleted(): bool
    {
        return ! is_null($this->safety_agreed_at);
    }

    /** Returns true if toolman/admin has physically verified APD at counter */
    public function isSafetyVerified(): bool
    {
        return ! is_null($this->safety_verified_at);
    }

    /** Cek apakah peminjaman ini melibatkan alat beresiko K3 */
    public function requiresSafetyClearance(): bool
    {
        return $this->item && $this->item->requiresSafetyInterlock();
    }

    public function safetyVerifier()
    {
        return $this->belongsTo(User::class, 'safety_verified_by');
    }

    public function safetyIncidents()
    {
        return $this->hasMany(SafetyIncident::class);
    }

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

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
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