<?php

namespace App\Models;

use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'category_id',
        'supplier_id',
        'stok_total',
        'stok_reguler',
        'stok_peminjaman',
        'harga',
        'gambar',
        'keterangan',
        'type',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok_total' => 'integer',
        'stok_reguler' => 'integer',
        'stok_peminjaman' => 'integer',
        'type' => ItemType::class,
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
    }

    public function getAvailableStokForBorrowing()
    {
        return $this->stok_peminjaman;
    }

    public function getAvailableStokForSale()
    {
        return $this->stok_reguler;
    }
}
