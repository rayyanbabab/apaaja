<?php
// Simple database check for users
$pdo = new PDO('mysql:host=127.0.0.1;dbname=artilia_db', 'root', '');
$stmt = $pdo->query('SELECT id, name, email, role FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total users: " . count($users) . "\n\n";

foreach ($users as $user) {
    echo "ID: {$user['id']}\n";
    echo "Name: {$user['name']}\n";
    echo "Email: {$user['email']}\n";
    echo "Role: {$user['role']}\n";
    echo "-------------------\n";
}