<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * 新しいメール確認通知を送信します。
     */
    public function store(Request $request): RedirectResponse
    {
        // ユーザーがメールをすでに確認している場合は、ダッシュボードにリダイレクトします。
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // メール確認通知を送信します。
        $request->user()->sendEmailVerificationNotification();

        // 直前のページにリダイレクトし、ステータスをセットします。
        return back()->with('status', 'verification-link-sent');
    }
}
