<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
{
    public function handleStockChange($itemId, $oldJumlah, $newJumlah, $status = null)
    {
        $selisih = $newJumlah - $oldJumlah;

        if ($selisih === 0) {
            return;
        }

        Inventory::create([
            'item_id' => $itemId,
            'tipe' => $selisih > 0 ? 'masuk' : 'keluar',
            'jumlah' => abs($selisih),
            'status' => $selisih < 0 ? $status : null,
        ]);
    }
}
