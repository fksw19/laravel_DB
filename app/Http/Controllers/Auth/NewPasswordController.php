<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * パスワードリセット画面を表示します。
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * 新しいパスワードリクエストを処理します。
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // フォームの入力値をバリデーションします。
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // パスワードのリセットを試みます。成功した場合はユーザーモデルに更新を行い、データベースに保存します。
        // 失敗した場合はエラーメッセージを返します。
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                // パスワードをハッシュ化してユーザーモデルに設定し、トークンを再生成します。
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // パスワードリセットイベントを発行します。
                event(new PasswordReset($user));
            }
        );

        // パスワードが正常にリセットされた場合、ログインページにリダイレクトします。
        // エラーがある場合は、エラーメッセージと共に元のページにリダイレクトします。
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
