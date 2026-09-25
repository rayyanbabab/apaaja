<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WorkshopNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'zone',
        'location_id',
        'pos_x',
        'pos_y',
        'width',
        'height',
        'rotation',
        'icon',
        'color_theme',
        'description',
        'status_override',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'pos_x'       => 'float',
        'pos_y'       => 'float',
        'width'       => 'float',
        'height'      => 'float',
        'rotation'    => 'integer',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    /**
     * Relasi ke Location (Rak / Area induk pada database inventaris)
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * Ambil item yang berada di node/rak ini
     */
    public function getItems(): Collection
    {
        if ($this->location_id) {
            return Item::where('location_id', $this->location_id)
                ->with(['category', 'location'])
                ->get();
        }

        // Jika tidak terikat lokasi, cari item berdasarkan bin_rack yang cocok dengan kode/nama node
        return Item::where('bin_rack', $this->code)
            ->orWhere('bin_rack', $this->name)
            ->with(['category', 'location'])
            ->get();
    }

    /**
     * Hitung status live telemetry untuk Digital Twin kanvas 2D
     */
    public function getLiveTelemetryAttribute(): array
    {
        $items = $this->getItems();
        $itemIds = $items->pluck('id')->toArray();

        // 1. Hitung total stok dan ketersediaan
        $totalItemsCount = $items->count();
        $totalStock = $items->sum('stok_total');
        $availableBorrowStock = $items->where('type', 'peminjaman')->sum('stok_peminjaman');
        $availableRegularStock = $items->where('type', 'stok')->sum('stok_reguler');

        // 2. Peminjaman Aktif (Borrowing & BorrowingRequest)
        $activeBorrowings = Borrowing::whereIn('item_id', $itemIds)
            ->where('status', 'dipinjam')
            ->with(['user', 'item'])
            ->get();

        $activeRequests = BorrowingRequest::whereIn('item_id', $itemIds)
            ->where('status', 'approved')
            ->with(['user', 'item'])
            ->get();

        $totalActiveLoans = $activeBorrowings->count() + $activeRequests->count();

        // Cek Overdue (Keterlambatan kembali)
        $nowDate = now()->toDateString();
        $overdueCount = $activeBorrowings->filter(function ($b) use ($nowDate) {
            return $b->tanggal_kembali_rencana && $b->tanggal_kembali_rencana->toDateString() < $nowDate;
        })->count();

        // 3. Maintenance & Kerusakan
        $damagedItemsCount = $items->where('kondisi', '!=', 'baik')->count();
        $inMaintenanceCount = Maintenance::whereIn('item_id', $itemIds)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        // 4. Kalibrasi Alat Ukur Presisi
        $expiredCalibrationCount = $items->where('calibration_status', 'expired')->count();
        $dueCalibrationCount = $items->where('calibration_status', 'due_soon')->count();

        // 5. Smart Logistics (Stok di bawah ROP / Menipis)
        $lowStockCount = $items->filter(function ($item) {
            return $item->isBelowROP();
        })->count();

        // 6. K3 Risk
        $highRiskCount = $items->where('safety_risk_level', 'high')->count();
        $mediumRiskCount = $items->where('safety_risk_level', 'medium')->count();

        // Tentukan status agregat Digital Twin untuk node ini
        if ($this->status_override) {
            $status = $this->status_override;
        } elseif ($inMaintenanceCount > 0 || $damagedItemsCount > 0) {
            $status = 'maintenance'; // 🔵 Sedang diservis / perbaikan
        } elseif ($overdueCount > 0 || $expiredCalibrationCount > 0) {
            $status = 'critical'; // 🔴 Kritis (overdue / kalibrasi expired)
        } elseif ($totalActiveLoans > 0 && $availableBorrowStock <= 0) {
            $status = 'in_use'; // 🟠 Sedang dipakai penuh
        } elseif ($totalActiveLoans > 0) {
            $status = 'partially_in_use'; // 🟡 Sebagian sedang dipinjam
        } elseif ($lowStockCount > 0 || $dueCalibrationCount > 0) {
            $status = 'warning'; // ⚠️ Perhatian (stok ROP / kalibrasi due)
        } elseif ($totalItemsCount > 0) {
            $status = 'ready'; // 🟢 Siap pakai / optimal
        } else {
            $status = 'idle'; // ⚪ Siap / Kosong
        }

        return [
            'status'                   => $status,
            'status_label'             => self::statusLabel($status),
            'status_color'             => self::statusColor($status),
            'total_items'              => $totalItemsCount,
            'total_stock'              => $totalStock,
            'available_borrow_stock'   => $availableBorrowStock,
            'available_regular_stock'  => $availableRegularStock,
            'active_loans_count'       => $totalActiveLoans,
            'overdue_loans_count'      => $overdueCount,
            'in_maintenance_count'     => $inMaintenanceCount,
            'damaged_items_count'      => $damagedItemsCount,
            'expired_calibration_count'=> $expiredCalibrationCount,
            'due_calibration_count'    => $dueCalibrationCount,
            'low_stock_count'          => $lowStockCount,
            'high_risk_count'          => $highRiskCount,
            'medium_risk_count'        => $mediumRiskCount,
            'items'                    => $items->map(function ($it) {
                return [
                    'id'                 => $it->id,
                    'nama'               => $it->nama,
                    'kode'               => $it->kode,
                    'type'               => $it->type?->value ?? (string) $it->type,
                    'tool_type'          => $it->tool_type,
                    'kondisi'            => $it->kondisi,
                    'stok_total'         => $it->stok_total,
                    'stok_peminjaman'    => $it->stok_peminjaman,
                    'stok_reguler'       => $it->stok_reguler,
                    'category_name'      => $it->category?->nama ?? 'Umum',
                    'gambar'             => $it->gambar ? asset('storage/' . $it->gambar) : null,
                    'calibration_status' => $it->calibration_status,
                    'safety_risk_level'  => $it->safety_risk_level,
                    'is_below_rop'       => $it->isBelowROP(),
                ];
            })->values()->toArray(),
            'active_borrowers'         => $activeBorrowings->map(function ($b) {
                return [
                    'user_name'    => $b->user?->name ?? 'User',
                    'item_name'    => $b->item?->nama ?? 'Alat',
                    'jumlah'       => $b->jumlah,
                    'due_date'     => $b->tanggal_kembali_rencana ? $b->tanggal_kembali_rencana->format('d M Y') : '-',
                    'is_overdue'   => $b->isOverdue(),
                ];
            })->values()->toArray(),
        ];
    }

    /**
     * Label representasi status
     */
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'ready'             => 'Siap Operasi (Optimal)',
            'in_use'            => 'Sedang Beroperasi Penuh',
            'partially_in_use'  => 'Sedang Dipinjam Sebagian',
            'warning'           => 'Perhatian (Stok Menipis / Kalibrasi)',
            'critical'          => 'Peringatan Kritis (Overdue / Expired)',
            'maintenance'       => 'Dalam Perawatan / Servis',
            'safety_hazard'     => 'Zona Resiko Tinggi (K3)',
            default             => 'Stasiun Siap (Idle)',
        };
    }

    /**
     * Definisi warna visual badge & beacon LED
     */
    public static function statusColor(string $status): array
    {
        return match ($status) {
            'ready' => [
                'bg'     => 'bg-emerald-500/10 dark:bg-emerald-950/40',
                'border' => 'border-emerald-500/30 dark:border-emerald-500/50',
                'text'   => 'text-emerald-600 dark:text-emerald-400',
                'badge'  => 'bg-emerald-500',
                'glow'   => 'shadow-emerald-500/40',
                'ring'   => 'ring-emerald-400/30',
            ],
            'in_use' => [
                'bg'     => 'bg-blue-500/10 dark:bg-blue-950/40',
                'border' => 'border-blue-500/30 dark:border-blue-500/50',
                'text'   => 'text-blue-600 dark:text-blue-400',
                'badge'  => 'bg-blue-500',
                'glow'   => 'shadow-blue-500/40',
                'ring'   => 'ring-blue-400/30',
            ],
            'partially_in_use' => [
                'bg'     => 'bg-cyan-500/10 dark:bg-cyan-950/40',
                'border' => 'border-cyan-500/30 dark:border-cyan-500/50',
                'text'   => 'text-cyan-600 dark:text-cyan-400',
                'badge'  => 'bg-cyan-500',
                'glow'   => 'shadow-cyan-500/40',
                'ring'   => 'ring-cyan-400/30',
            ],
            'warning' => [
                'bg'     => 'bg-amber-500/10 dark:bg-amber-950/40',
                'border' => 'border-amber-500/30 dark:border-amber-500/50',
                'text'   => 'text-amber-600 dark:text-amber-400',
                'badge'  => 'bg-amber-500',
                'glow'   => 'shadow-amber-500/40',
                'ring'   => 'ring-amber-400/30',
            ],
            'critical' => [
                'bg'     => 'bg-rose-500/10 dark:bg-rose-950/40',
                'border' => 'border-rose-500/30 dark:border-rose-500/50',
                'text'   => 'text-rose-600 dark:text-rose-400',
                'badge'  => 'bg-rose-500',
                'glow'   => 'shadow-rose-500/40',
                'ring'   => 'ring-rose-400/30',
            ],
            'maintenance' => [
                'bg'     => 'bg-violet-500/10 dark:bg-violet-950/40',
                'border' => 'border-violet-500/30 dark:border-violet-500/50',
                'text'   => 'text-violet-600 dark:text-violet-400',
                'badge'  => 'bg-violet-500',
                'glow'   => 'shadow-violet-500/40',
                'ring'   => 'ring-violet-400/30',
            ],
            'safety_hazard' => [
                'bg'     => 'bg-purple-500/10 dark:bg-purple-950/40',
                'border' => 'border-purple-500/30 dark:border-purple-500/50',
                'text'   => 'text-purple-600 dark:text-purple-400',
                'badge'  => 'bg-purple-500',
                'glow'   => 'shadow-purple-500/40',
                'ring'   => 'ring-purple-400/30',
            ],
            default => [
                'bg'     => 'bg-slate-500/10 dark:bg-slate-900/40',
                'border' => 'border-slate-500/30 dark:border-slate-700/50',
                'text'   => 'text-slate-600 dark:text-slate-400',
                'badge'  => 'bg-slate-500',
                'glow'   => 'shadow-slate-500/40',
                'ring'   => 'ring-slate-400/30',
            ],
        };
    }

    /**
     * Blueprint Default Standar Bengkel Vokasi
     */
    public static function getDefaultBlueprint(): array
    {
        // Temukan ID lokasi yang sudah ada
        $rak1 = Location::where('kode', 'R11')->orWhere('name', 'like', '%Rak 1%')->first();
        $rakBaut = Location::where('kode', 'RB')->orWhere('name', 'like', '%BAUT%')->first();
        $lemariPinjam = Location::where('kode', 'LP')->orWhere('name', 'like', '%Peminjaman%')->first();

        return [
            // Zona A: Machining & Fabrikasi (Kiri Atas & Tengah)
            [
                'name'         => 'Mesin Bubut Bench Lathe 350mm #1',
                'code'         => 'MCH-BBT-01',
                'type'         => 'machine',
                'zone'         => 'machining',
                'location_id'  => $lemariPinjam?->id,
                'pos_x'        => 6.00,
                'pos_y'        => 12.00,
                'width'        => 18.00,
                'height'       => 16.00,
                'rotation'     => 0,
                'icon'         => 'lathe',
                'color_theme'  => 'blue',
                'description'  => 'Mesin bubut presisi tinggi untuk pembubutan logam & pengerjaan poros silindris.',
            ],
            [
                'name'         => 'Mesin Milling & Frais CNC #2',
                'code'         => 'MCH-MLG-02',
                'type'         => 'machine',
                'zone'         => 'machining',
                'location_id'  => null,
                'pos_x'        => 28.00,
                'pos_y'        => 12.00,
                'width'        => 18.00,
                'height'       => 16.00,
                'rotation'     => 0,
                'icon'         => 'mill',
                'color_theme'  => 'purple',
                'description'  => 'Mesin frais vertikal 3-Axis untuk pembuatan alur, kantung, dan permukaan presisi.',
            ],
            [
                'name'         => 'Bor Duduk Presisi & Gerinda Bangku',
                'code'         => 'MCH-DRL-03',
                'type'         => 'machine',
                'zone'         => 'machining',
                'location_id'  => null,
                'pos_x'        => 6.00,
                'pos_y'        => 34.00,
                'width'        => 18.00,
                'height'       => 14.00,
                'rotation'     => 0,
                'icon'         => 'drill',
                'color_theme'  => 'amber',
                'description'  => 'Stasiun pengeboran berdiameter hingga 25mm dan penajaman pahat potong.',
            ],

            // Zona B: Tool Crib & Storage (Kanan Atas)
            [
                'name'         => 'Lemari Alat Ukur Presisi & Kalibrasi',
                'code'         => 'LMR-CAL-01',
                'type'         => 'cabinet',
                'zone'         => 'tool_crib',
                'location_id'  => $lemariPinjam?->id,
                'pos_x'        => 52.00,
                'pos_y'        => 12.00,
                'width'        => 18.00,
                'height'       => 16.00,
                'rotation'     => 0,
                'icon'         => 'micrometer',
                'color_theme'  => 'emerald',
                'description'  => 'Penyimpanan mikrometer luar/dalam, dial indicator, blok ukur, dan vernier height gauge.',
            ],
            [
                'name'         => 'Rak Perkakas Utama Mekanik',
                'code'         => 'RK-MEK-01',
                'type'         => 'rack',
                'zone'         => 'tool_crib',
                'location_id'  => $rak1?->id ?? $rakBaut?->id,
                'pos_x'        => 74.00,
                'pos_y'        => 12.00,
                'width'        => 20.00,
                'height'       => 16.00,
                'rotation'     => 0,
                'icon'         => 'rack',
                'color_theme'  => 'emerald',
                'description'  => 'Rak perkakas tangan: kunci pas, tang, palu tembaga, obeng torsi, dan gergaji besi.',
            ],
            [
                'name'         => 'Rak Baut, Fastener & Komponen Jadi',
                'code'         => 'RK-BAUT-02',
                'type'         => 'rack',
                'zone'         => 'tool_crib',
                'location_id'  => $rakBaut?->id ?? $rak1?->id,
                'pos_x'        => 74.00,
                'pos_y'        => 34.00,
                'width'        => 20.00,
                'height'       => 14.00,
                'rotation'     => 0,
                'icon'         => 'box',
                'color_theme'  => 'amber',
                'description'  => 'Bin modular baut titanium, mur M6/M8/M10, ring plat, pin dowel, dan pegas.',
            ],

            // Zona C: Meja Kerja & Perakitan (Tengah)
            [
                'name'         => 'Meja Perakitan & QC Mekanik',
                'code'         => 'WB-ASM-01',
                'type'         => 'workbench',
                'zone'         => 'assembly',
                'location_id'  => null,
                'pos_x'        => 28.00,
                'pos_y'        => 46.00,
                'width'        => 18.00,
                'height'       => 18.00,
                'rotation'     => 0,
                'icon'         => 'table',
                'color_theme'  => 'blue',
                'description'  => 'Meja kerja kayu keras berlapis pelat baja untuk assembly, fitting, dan penandaan benda kerja.',
            ],
            [
                'name'         => 'Stasiun Solder & Elektronika Terpadu',
                'code'         => 'WB-SLD-02',
                'type'         => 'workbench',
                'zone'         => 'assembly',
                'location_id'  => $lemariPinjam?->id,
                'pos_x'        => 52.00,
                'pos_y'        => 46.00,
                'width'        => 18.00,
                'height'       => 18.00,
                'rotation'     => 0,
                'icon'         => 'solder',
                'color_theme'  => 'cyan',
                'description'  => 'Area soldering station bersuhu terkendali, multitester digital, desoldering pump, dan fume extractor.',
            ],

            // Zona D: Safety Station & K3 (Kiri Bawah & Pintu Masuk)
            [
                'name'         => 'Kios AI Defect Scanner & APD Counter',
                'code'         => 'KSK-AI-01',
                'type'         => 'safety_kiosk',
                'zone'         => 'safety',
                'location_id'  => null,
                'pos_x'        => 6.00,
                'pos_y'        => 74.00,
                'width'        => 18.00,
                'height'       => 18.00,
                'rotation'     => 0,
                'icon'         => 'shield',
                'color_theme'  => 'rose',
                'description'  => 'Kios kamera computer vision untuk inspeksi cacat pahat/alat dan verifikasi fisik APD sebelum pinjam.',
            ],
            [
                'name'         => 'Pos APAR & Kotak P3K Bengkel',
                'code'         => 'POS-P3K-01',
                'type'         => 'safety_kiosk',
                'zone'         => 'safety',
                'location_id'  => null,
                'pos_x'        => 28.00,
                'pos_y'        => 76.00,
                'width'        => 14.00,
                'height'       => 16.00,
                'rotation'     => 0,
                'icon'         => 'flame',
                'color_theme'  => 'rose',
                'description'  => 'Tabung Pemadam Api Ringan (APAR CO2 & Powder) dan stasiun P3K darurat kecelakaan kerja.',
            ],

            // Zona E: Logistik Inbound/Outbound (Kanan Bawah)
            [
                'name'         => 'Dermaga Logistik Material & Karantina',
                'code'         => 'BAY-LOG-01',
                'type'         => 'logistics_bay',
                'zone'         => 'logistics',
                'location_id'  => null,
                'pos_x'        => 70.00,
                'pos_y'        => 72.00,
                'width'        => 24.00,
                'height'       => 20.00,
                'rotation'     => 0,
                'icon'         => 'truck',
                'color_theme'  => 'slate',
                'description'  => 'Area bongkar muat material baku (batang baja, aluminium) dan karantina alat rusak menunggu scrap/servis.',
            ],
        ];
    }
}
