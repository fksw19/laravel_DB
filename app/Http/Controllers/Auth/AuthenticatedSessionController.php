<?php

// App\Http\Controllers\Auth ネームスペースを使用します。
namespace App\Http\Controllers\Auth;

// Laravel コントローラークラスを使用します。
use App\Http\Controllers\Controller;

// ログインリクエストを使用します。
use App\Http\Requests\Auth\LoginRequest;

// リダイレクトレスポンスを使用します。
use Illuminate\Http\RedirectResponse;

// リクエストを使用します。
use Illuminate\Http\Request;

// 認証ファサードを使用します。
use Illuminate\Support\Facades\Auth;

// ビューを使用します。
use Illuminate\View\View;

// Controller クラスを継承した AuthenticatedSessionController クラスを定義します。
class AuthenticatedSessionController extends Controller
{
    /**
     * ログインビューを表示します。
     */
    public function create(): View
    {
        // auth.login ビューを表示します。
        return view('auth.login');
    }

    /**
     * 認証リクエストを処理します。
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // リクエストを認証します。
        $request->authenticate();

        // セッションを再生成します。
        $request->session()->regenerate();

        // インデントされた先（デフォルトの場合はダッシュボード）へリダイレクトします。
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * 認証されたセッションを破棄します。
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 'web' ガードでログアウトします。
        Auth::guard('web')->logout();

        // セッションを無効にします。
        $request->session()->invalidate();

        // CSRF トークンを再生成します。
        $request->session()->regenerateToken();

        // '/' へリダイレクトします。
        return redirect('/');
    }
}
