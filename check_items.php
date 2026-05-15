<?php

require __DIR__.'/bootstrap/app.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$items = DB::table('items')->get();

echo "Total items: " . count($items) . "\n\n";

foreach ($items as $item) {
    echo "ID: {$item->id}\n";
    echo "Name: {$item->nama}\n";
    echo "Type: {$item->type}\n";
    echo "Stock (peminjaman): {$item->stok_peminjaman}\n";
    echo "Stock (reguler): {$item->stok_reguler}\n";
    echo "-------------------\n";
}