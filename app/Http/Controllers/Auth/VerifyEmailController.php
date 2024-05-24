<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * 認証済みユーザーのメールアドレスを確認済みとしてマークします。
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // ユーザーのメールアドレスが既に確認済みの場合は、ダッシュボードにリダイレクトします。
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // メールアドレスを確認済みにマークします。
        if ($request->user()->markEmailAsVerified()) {
            // 確認イベントを発行します。
            event(new Verified($request->user()));
        }

        // ダッシュボードにリダイレクトします。
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
