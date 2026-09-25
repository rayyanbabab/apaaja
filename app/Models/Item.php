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
        'kondisi',
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
        // K3 Safety Interlock
        'safety_risk_level',
        'required_apd',
        'safety_instruction',
        'k3_quiz_required',
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
        'required_apd'         => 'array',
        'k3_quiz_required'     => 'boolean',
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
        return $this->calibration_due_date->lt(today());
    }

    /** Cek apakah alat ukur akan kedaluwarsa dalam 14 hari */
    public function isCalibrationDueSoon(): bool
    {
        if ($this->tool_type !== 'measuring_tool' || !$this->calibration_due_date) {
            return false;
        }
        return !$this->isCalibrationExpired() && $this->calibration_due_date->diffInDays(today(), false) <= 14;
    }

    /** Cek apakah alat layak dipinjam untuk proses manufaktur / praktikum (K3 Interlock) */
    public function canBeBorrowedForManufacturing(): bool
    {
        // K3 Safety Interlock: Alat rusak berat ditarik otomatis dari sirkulasi
        if ($this->kondisi === 'rusak_berat') {
            return false;
        }

        // K3 Precision Interlock: Alat ukur kedaluwarsa kalibrasi tidak boleh dipinjam
        if ($this->tool_type === 'measuring_tool' && $this->isCalibrationExpired()) {
            return false;
        }

        return true;
    }

    /** Cek apakah alat terkena K3 Lockout otomatis akibat kerusakan kritis */
    public function isK3Lockout(): bool
    {
        return $this->kondisi === 'rusak_berat';
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

        if ($this->calibration_due_date->lt(today())) {
            $this->calibration_status = 'expired';
        } elseif ($this->calibration_due_date->diffInDays(today(), false) <= 14) {
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
        $this->stok_total = (int)($this->stok_reguler ?? 0) + (int)($this->stok_peminjaman ?? 0);
        $this->save();
    }

    public function addStok($jumlah, $tipe = 'reguler')
    {
        if ($tipe === 'peminjaman') {
            $this->increment('stok_peminjaman', $jumlah);
        } else {
            $this->increment('stok_reguler', $jumlah);
        }
        $this->refresh();
        $this->updateStokTotal();
    }

    public function reduceStok($jumlah, $tipe = 'reguler')
    {
        if ($tipe === 'peminjaman') {
            $this->decrement('stok_peminjaman', $jumlah);
        } else {
            $this->decrement('stok_reguler', $jumlah);
        }
        $this->refresh();
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

    /** Relasi ke Safety Incidents */
    public function safetyIncidents()
    {
        return $this->hasMany(SafetyIncident::class);
    }

    /** Cek apakah item mewajibkan interlock keselamatan (medium/high) */
    public function requiresSafetyInterlock(): bool
    {
        return in_array($this->safety_risk_level, ['medium', 'high']);
    }

    public function isHighRiskSafety(): bool
    {
        return $this->safety_risk_level === 'high';
    }

    public function isMediumRiskSafety(): bool
    {
        return $this->safety_risk_level === 'medium';
    }

    /** Badge visual resiko K3 */
    public function getRiskBadgeAttribute(): array
    {
        return match ($this->safety_risk_level) {
            'high'   => ['label' => 'High Risk (Bahaya Tinggi)', 'class' => 'bg-red-500/10 text-red-500 border border-red-500/20 dark:bg-red-950/40 dark:text-red-400 dark:border-red-800/40', 'dot' => 'bg-red-500'],
            'medium' => ['label' => 'Medium Risk (Waspada)', 'class' => 'bg-amber-500/10 text-amber-600 border border-amber-500/20 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/40', 'dot' => 'bg-amber-500'],
            default  => ['label' => 'Low Risk (Standar)', 'class' => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800/40', 'dot' => 'bg-emerald-500'],
        };
    }

    /** Master Katalog APD Laboratorium Teknik */
    public static function getApdCatalog(): array
    {
        return [
            'safety_glasses' => [
                'name' => 'Kacamata Pengaman (Safety Glasses)',
                'icon' => 'glasses',
                'desc' => 'Melindungi mata dari percikan gram, debu sisa bubut, atau radiasi sinar.',
            ],
            'wearpack' => [
                'name' => 'Wearpack / Baju Kerja Praktik',
                'icon' => 'shirt',
                'desc' => 'Kain tebal anti-sobek untuk melindungi tubuh dari kontak panas dan goresan.',
            ],
            'safety_shoes' => [
                'name' => 'Sepatu Pengaman (Safety Shoes)',
                'icon' => 'footprints',
                'desc' => 'Ujung baja pelindung benturan benda jatuh dan sol tahan oli / slip.',
            ],
            'face_shield' => [
                'name' => 'Pelindung Wajah (Face Shield)',
                'icon' => 'shield',
                'desc' => 'Wajib untuk pengelasan, gerinda potong, atau percikan bahan kimia.',
            ],
            'earmuff' => [
                'name' => 'Pelindung Telinga (Ear Muff / Ear Plug)',
                'icon' => 'volume-x',
                'desc' => 'Meredam kebisingan tinggi pada area mesin milling atau kompresor.',
            ],
            'gloves' => [
                'name' => 'Sarung Tangan Kerja (Safety Gloves)',
                'icon' => 'hand',
                'desc' => 'Sarung tangan kulit / tahan panas sesuai tipe pengerjaan.',
            ],
            'respirator' => [
                'name' => 'Masker / Respirator Debu & Uap',
                'icon' => 'air-vent',
                'desc' => 'Menyaring asap solder, uap thinner, atau serbuk logam.',
            ],
        ];
    }

    /** Label APD yang diwajibkan untuk item ini */
    public function getRequiredApdDetailsAttribute(): array
    {
        $catalog = self::getApdCatalog();
        $required = $this->required_apd ?? [];
        $result = [];

        foreach ($required as $apdKey) {
            if (isset($catalog[$apdKey])) {
                $result[$apdKey] = $catalog[$apdKey];
            }
        }

        return $result;
    }
}

