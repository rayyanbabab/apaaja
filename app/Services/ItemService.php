<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Item;

class ItemService
{
    public function createOrUpdateItem(array $data, $gambar = null)
    {
        try {
            $item = Item::where('nama', $data['nama'])->first();
            $oldStok = 0;

            if ($item) {
                $oldStok = $item->stok_total;
            } else {
                $item = new Item;
                $item->nama = $data['nama'];
                $item->category_id = $data['category_id'] ?? null;
                $item->supplier_id = $data['supplier_id'] ?? null;
                $item->harga = $data['harga'];
                $item->keterangan = $data['keterangan'] ?? null;
                $item->type = $data['type'];
            }
            if ($data['type'] === 'peminjaman') {
                $item->stok_peminjaman = $data['stok'];
                $item->stok_reguler = 0;
            } else {
                $item->stok_reguler = $data['stok'];
                $item->stok_peminjaman = 0;
            }
            $item->stok_total = $data['stok'];
            if ($gambar && $gambar instanceof \Illuminate\Http\UploadedFile && $gambar->isValid()) {
                if ($item->gambar && file_exists(public_path($item->gambar))) {
                    unlink(public_path($item->gambar));
                }
                $imagesPath = public_path('images');
                if (! file_exists($imagesPath)) {
                    mkdir($imagesPath, 0755, true);
                }
                $fileName = time().'_'.$gambar->getClientOriginalName();
                $gambar->move($imagesPath, $fileName);
                $item->gambar = 'images/'.$fileName;
            }
        } catch (\Exception $e) {
            \Log::error('Error in createOrUpdateItem: '.$e->getMessage());
            throw $e;
        }

        $item->save();

        $selisihStok = $item->stok_total - $oldStok;
        if ($selisihStok !== 0) {
            Inventory::create([
                'item_id' => $item->id,
                'tipe' => $selisihStok > 0 ? 'masuk' : 'keluar',
                'jumlah' => abs($selisihStok),
            ]);
        }

        return $item;
    }

    public function deleteItem(Item $item): void
    {
        if ($item->gambar && file_exists(public_path($item->gambar))) {
            unlink(public_path($item->gambar));
        }

        $item->delete();
    }
}
