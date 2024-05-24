<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    // パスワード確認画面を表示します。
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    // ユーザーのパスワードを確認します。
    public function store(Request $request): RedirectResponse
    {
        // ユーザーの入力したパスワードを認証します。
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // 認証が失敗した場合、エラーメッセージを投げます。
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // パスワード確認が成功した場合、セッションに確認時刻を記録します。
        $request->session()->put('auth.password_confirmed_at', time());

        // ダッシュボードへリダイレクトします。
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
