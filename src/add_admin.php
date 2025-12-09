<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// 既にadminユーザーが存在するかチェック
$existingUser = User::where('email', 'admin')->first();

if ($existingUser) {
    echo "ユーザー 'admin' は既に存在します。\n";
    echo "ID: {$existingUser->id}\n";
    echo "Name: {$existingUser->name}\n";
    echo "Role: {$existingUser->role}\n";
} else {
    // 新しい管理者アカウントを作成
    $user = User::create([
        'name' => 'admin',
        'email' => 'admin',
        'password' => Hash::make('test1234'),
        'role' => 9, // ROLE_ADMIN
        'profile_completed_flg' => 1,
        'delete_flg' => 0,
    ]);

    echo "管理者アカウント 'admin' を作成しました。\n";
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Password: test1234\n";
}

