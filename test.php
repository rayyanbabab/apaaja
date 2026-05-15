<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/content/list-users', 'GET');
$controller = new App\Http\Controllers\UsersAccountController();
$view = $controller->list($request);
$users = $view->getData()['users'];
echo "Controller returned " . count($users) . " users.\n";
foreach ($users as $user) {
    echo "- " . $user->name . " (Active: " . $user->is_active . ")\n";
}
