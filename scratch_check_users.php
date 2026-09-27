<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all();
foreach ($users as $user) {
    $matchesPassword = Hash::check('password', $user->password);
    echo "ID: {$user->id} | Email: {$user->email} | Matches 'password': " . ($matchesPassword ? 'YES' : 'NO') . " | Hash: " . substr($user->password, 0, 20) . "...\n";
}
