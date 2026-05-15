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
    ];

    protected $casts = [
        'harga'           => 'integer',
        'stok_total'      => 'integer',
        'stok_reguler'    => 'integer',
        'stok_peminjaman' => 'integer',
        'type'            => ItemType::class,
    ];

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
