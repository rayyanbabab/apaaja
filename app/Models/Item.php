<?php

namespace App\Models;

use App\Enums\ItemType;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'category_id',
        'supplier_id',
        'location_id',
        'stok_total',
        'stok_reguler',
        'stok_peminjaman',
        'harga',
        'gambar',
        'keterangan',
        'type',
        'tool_type',
        'calibration_due_date',
        'calibration_status',
        'calibration_certificate_number',
        'calibration_notes',
        'tool_life_hours',
        'max_tool_life_hours',
        'bin_rack',
        'lead_time_days',
        'daily_usage_rate',
        'safety_stock',
        'holding_cost',
        'order_cost',
    ];

    protected $casts = [
        'harga'                => 'integer',
        'stok_total'           => 'integer',
        'stok_reguler'         => 'integer',
        'stok_peminjaman'      => 'integer',
        'type'                 => ItemType::class,
        'calibration_due_date' => 'date',
        'tool_life_hours'      => 'float',
        'max_tool_life_hours'  => 'float',
        'lead_time_days'       => 'integer',
        'daily_usage_rate'     => 'float',
        'safety_stock'         => 'integer',
        'holding_cost'         => 'integer',
        'order_cost'           => 'integer',
    ];

    /** Hitung Reorder Point (ROP): (Laju Pakai x Lead Time) + Safety Stock */
    public function calculateROP(): int
    {
        $daily = $this->daily_usage_rate ?: 1.0;
        $lead  = $this->lead_time_days ?: 3;
        $safety = $this->safety_stock ?: 0;
        return (int) ceil(($daily * $lead) + $safety);
    }

    /** Cek apakah stok barang saat ini berada pada atau di bawah titik aman ROP */
    public function isBelowROP(): bool
    {
        return $this->stok_total <= $this->calculateROP();
    }

    /** Hitung Economic Order Quantity (EOQ): sqrt((2 * D * S) / H) */
    public function calculateEOQ(): int
    {
        $annualDemand = ($this->daily_usage_rate ?: 1.0) * 365;
        $orderCost    = $this->order_cost ?: 50000;
        $holdingCost  = $this->holding_cost ?: 5000;

        if ($holdingCost <= 0) {
            $holdingCost = 5000;
        }

        $eoq = sqrt((2 * $annualDemand * $orderCost) / $holdingCost);
        return (int) max(1, round($eoq));
    }

    /** Valuasi total aset item saat ini */
    public function getValuationAttribute(): int
    {
        return (int) ($this->stok_total * $this->harga);
    }

    /** Cek apakah alat ukur sudah kedaluwarsa masa kalibrasinya */
    public function isCalibrationExpired(): bool
    {
        if ($this->tool_type !== 'measuring_tool') {
            return false;
        }
        if (!$this->calibration_due_date) {
            return false;
        }
        return $this->calibration_due_date->isPast();
    }

    /** Cek apakah alat ukur akan kedaluwarsa dalam 14 hari */
    public function isCalibrationDueSoon(): bool
    {
        if ($this->tool_type !== 'measuring_tool' || !$this->calibration_due_date) {
            return false;
        }
        return !$this->isCalibrationExpired() && $this->calibration_due_date->diffInDays(now()) <= 14;
    }

    /** Cek apakah alat layak dipinjam untuk proses manufaktur */
    public function canBeBorrowedForManufacturing(): bool
    {
        if ($this->tool_type === 'measuring_tool' && $this->isCalibrationExpired()) {
            return false;
        }
        return true;
    }

    /** Sinkronkan status kalibrasi otomatis berdasarkan tanggal */
    public function syncCalibrationStatus(): void
    {
        if ($this->tool_type !== 'measuring_tool') {
            $this->calibration_status = 'not_applicable';
            return;
        }

        if (!$this->calibration_due_date) {
            $this->calibration_status = 'calibrated';
            return;
        }

        if ($this->calibration_due_date->isPast()) {
            $this->calibration_status = 'expired';
        } elseif ($this->calibration_due_date->diffInDays(now()) <= 14) {
            $this->calibration_status = 'due_soon';
        } else {
            $this->calibration_status = 'calibrated';
        }
    }

    /** Menghitung persentase keausan mata pahat / tool life */
    public function getToolLifeProgressPercentage(): float
    {
        if (!$this->max_tool_life_hours || $this->max_tool_life_hours <= 0) {
            return 0.0;
        }
        $pct = ($this->tool_life_hours / $this->max_tool_life_hours) * 100;
        return (float) min(100, round($pct, 1));
    }

    /** Jumlah unit yang sedang in_repair (dari tabel maintenances). */
    public function getStokInRepairAttribute(): int
    {
        return $this->maintenances()->where('status', 'in_repair')->sum('jumlah');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function maintenances()
    {
        return $this->hasMany(\App\Models\Maintenance::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function jumlahMasuk()
    {
        return $this->inventories()->where('tipe', 'masuk')->sum('jumlah');
    }

    public function jumlahKeluar()
    {
        return $this->inventories()->where('tipe', 'keluar')->sum('jumlah');
    }

    public function updateStokTotal()
    {
        $this->stok_total = $this->stok_reguler + $this->stok_peminjaman;
        $this->save();
    }

    public function addStok($jumlah, $tipe = 'reguler')
    {
        if ($tipe === 'peminjaman') {
            $this->increment('stok_peminjaman', $jumlah);
        } else {
            $this->increment('stok_reguler', $jumlah);
        }
        $this->updateStokTotal();
    }

    public function reduceStok($jumlah, $tipe = 'reguler')
    {
        if ($tipe === 'peminjaman') {
            $this->decrement('stok_peminjaman', $jumlah);
        } else {
            $this->decrement('stok_reguler', $jumlah);
        }
        $this->updateStokTotal();
        $this->checkAndNotifyLowStock();
    }

    public function getAvailableStokForBorrowing()
    {
        return $this->stok_peminjaman;
    }

    public function getAvailableStokForSale()
    {
        return $this->stok_reguler;
    }

    /**
     * Cek stok setelah pengurangan, kirim notifikasi ke admin + operator jika di bawah threshold.
     * Cooldown 24 jam per item agar tidak spam.
     */
    public function checkAndNotifyLowStock(): void
    {
        $threshold = (int) Setting::get('low_stock_threshold', 5);
        $enabled   = Setting::get('enable_low_stock_alert', true);

        if (! $enabled || $this->stok_total > $threshold) {
            return;
        }

        // Cek apakah sudah kirim notif untuk item ini dalam 24 jam terakhir
        $alreadySent = DB::table('notifications')
            ->where('type', LowStockNotification::class)
            ->where('data->item_id', $this->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        if ($alreadySent) {
            return;
        }

        // Refresh data terbaru sebelum kirim notif
        $this->refresh();

        $staffReceivers = User::whereIn('role', ['admin', 'operator'])->get();
        foreach ($staffReceivers as $staff) {
            $staff->notify(new LowStockNotification($this, $threshold));
        }
    }
}
