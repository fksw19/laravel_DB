<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * 登録フォームを表示します。
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * 登録リクエストを処理します。
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // フォームの入力値をバリデーションします。
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 新しいユーザーレコードを作成します。
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 登録イベントを発行します。
        event(new Registered($user));

        // ユーザーをログイン状態にします。
        Auth::login($user);

        // ダッシュボードページにリダイレクトします。
        return redirect(route('dashboard', absolute: false));
    }
}
