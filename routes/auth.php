<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// ゲストユーザーのミドルウェアグループ
Route::middleware('guest')->group(function () {
    // 登録ページ
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    // 登録処理
    Route::post('register', [RegisteredUserController::class, 'store']);

    // ログインページ
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    // ログイン処理
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // パスワードリセットページ
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    // パスワードリセットメール送信
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    // パスワードリセット
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    // パスワード保存
    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

// 認証済みユーザーのミドルウェアグループ
Route::middleware('auth')->group(function () {
    // メール確認通知
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    // メール確認
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    // メール確認通知の再送信
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    // パスワード確認ページ
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    // パスワード確認処理
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // パスワード更新
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // ログアウト
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
