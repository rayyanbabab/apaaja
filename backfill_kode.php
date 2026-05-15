<?php

use App\Models\Item;

$items = Item::whereNull('kode')->orWhere('kode', '')->get();
$count = 0;
foreach ($items as $item) {
    $item->update(['kode' => 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT)]);
    $count++;
    echo "Updated item #{$item->id}: {$item->nama}\n";
}
echo "Done. {$count} item(s) updated.\n";
