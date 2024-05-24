<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * ユーザーのプロフィール編集フォームを表示します。
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * ユーザーのプロフィール情報を更新します。
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // フォームのバリデーション済みデータをユーザーモデルに適用します。
        $request->user()->fill($request->validated());

        // メールアドレスが変更された場合は、メール確認済みフラグをリセットします。
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // ユーザーモデルを保存します。
        $request->user()->save();

        // プロフィール編集ページにリダイレクトし、ステータスをセットします。
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * ユーザーアカウントを削除します。
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ユーザーアカウント削除のためのバリデーションを行います。
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // ログアウト処理を行います。
        Auth::logout();

        // ユーザーを削除します。
        $request->user()->delete();

        // セッションを無効化し、新しいトークンを生成します。
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ホームページにリダイレクトします。
        return Redirect::to('/');
    }
}
