<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * ユーザーのパスワードを更新します。
     */
    public function update(Request $request): RedirectResponse
    {
        // フォームの入力値をバリデーションします。
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'], // 現在のパスワードのバリデーション
            'password' => ['required', Password::defaults(), 'confirmed'], // 新しいパスワードのバリデーション
        ]);

        // ユーザーモデルを更新し、新しいパスワードを保存します。
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 直前のページにリダイレクトし、ステータスをセットします。
        return back()->with('status', 'password-updated');
    }
}
