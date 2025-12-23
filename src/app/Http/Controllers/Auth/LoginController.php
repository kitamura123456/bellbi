<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログインフォーム表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            // ロールに応じてリダイレクト先を出し分け
            // intended()は、認証が必要なページにアクセスしようとしたときに保存されたURLを優先的に使用する
            if ($user->role === User::ROLE_PERSONAL) {
                // カートや注文確認ページから来た場合は、そのURLに戻る
                // カートページから来た場合は、カートページに戻る
                $intendedUrl = $request->session()->get('url.intended');
                if ($intendedUrl && str_contains($intendedUrl, route('orders.checkout'))) {
                    // 注文確認ページから来た場合は、カートページに戻す
                    return redirect()->route('cart.index');
                }
                return redirect()->intended('/mypage');
            }

            if ($user->role === User::ROLE_COMPANY) {
                return redirect()->intended('/company');
            }

            if ($user->role === User::ROLE_ADMIN) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}


