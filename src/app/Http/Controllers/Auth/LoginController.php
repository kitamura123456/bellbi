<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email' => ['nullable', 'string'],
            'password' => ['required'],
        ]);

        $loginValue = $credentials['email'] ?? '';
        $password = $credentials['password'];

        // メールアドレス形式かどうかをチェック
        $isEmail = filter_var($loginValue, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // メールアドレス形式の場合は通常の認証を試みる
            if (Auth::attempt(['email' => $loginValue, 'password' => $password], $request->boolean('remember'))) {
                $request->session()->regenerate();
                return $this->redirectAfterLogin($request);
            }
        } else {
            // メールアドレス形式でない場合は、ユーザー名でログインを試みる
            $user = User::where('name', $loginValue)->first();
            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                return $this->redirectAfterLogin($request);
            }
        }

        return back()->withErrors([
            'email' => 'メールアドレス/ユーザー名またはパスワードが正しくありません。',
        ])->onlyInput('email');
    }

    /**
     * ログイン後のリダイレクト処理
     */
    private function redirectAfterLogin(Request $request): RedirectResponse
    {
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
            // 予約ページから来た場合は、そのURLに戻る
            if ($intendedUrl && (str_contains($intendedUrl, '/reservations/store/') || str_contains($intendedUrl, route('reservations.booking')))) {
                return redirect()->intended();
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


