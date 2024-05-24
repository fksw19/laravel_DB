<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * パスワードリセットリンク要求画面を表示します。
     */
    public function create(): View
    {
        // パスワードリセットリンク要求画面のビューを返します。
        return view('auth.forgot-password');
    }

    /**
     * 受信したパスワードリセットリンク要求を処理します。
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 入力値のバリデーションを行います。
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // パスワードリセットリンクをこのユーザーに送信します。
        // リンクの送信を試みた後、レスポンスを調査してユーザーに表示するメッセージを確認します。
        // 最後に、適切なレスポンスを送信します。
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // リセットリンクの送信が成功した場合は、成功メッセージを含むリダイレクトを返します。
        // 失敗した場合は、エラーメッセージを含むリダイレクトを返します。
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
