<?php
// Simple database check
$pdo = new PDO('mysql:host=127.0.0.1;dbname=artilia_db', 'root', '');
$stmt = $pdo->query('SELECT id, nama, type, stok_peminjaman, stok_reguler FROM items');
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total items: " . count($items) . "\n\n";

foreach ($items as $item) {
    echo "ID: {$item['id']}\n";
    echo "Name: {$item['nama']}\n";
    echo "Type: {$item['type']}\n";
    echo "Stock (peminjaman): {$item['stok_peminjaman']}\n";
    echo "Stock (reguler): {$item['stok_reguler']}\n";
    echo "-------------------\n";
}