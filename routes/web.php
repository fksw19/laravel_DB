<?php

// ProfileController クラスを使用します。
use App\Http\Controllers\ProfileController;

// Laravel のルーティング機能を使用します。
use Illuminate\Support\Facades\Route;

// ホームページの表示用ルート
Route::get('/', function () {
    // welcome ビューを返します。
    return view('welcome');
});

// ダッシュボードの表示用ルート。認証とメール認証が必要です。
Route::get('/dashboard', function () {
    // dashboard ビューを返します。
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 認証が必要なグループ内のルート
Route::middleware('auth')->group(function () {
    // プロフィールの編集用ルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // プロフィールの更新用ルート
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // プロフィールの削除用ルート
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 認証関連のルートを別ファイルから読み込みます。
require __DIR__.'/auth.php';
