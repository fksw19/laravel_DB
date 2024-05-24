<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * メール確認プロンプトを表示します。
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // ユーザーがメールを確認済みの場合は、ダッシュボードにリダイレクトします。
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
