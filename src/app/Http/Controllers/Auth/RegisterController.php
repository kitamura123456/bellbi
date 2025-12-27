<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * 会員登録フォーム（エンドユーザー用）表示
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 会員登録処理
     */
    public function register(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // emailが提供された場合のみバリデーションを適用
        if ($request->filled('email')) {
            $rules['email'] = ['nullable', 'string', 'email', 'max:255', 'unique:users,email'];
        } else {
            $rules['email'] = ['nullable', 'string', 'email', 'max:255'];
        }

        $data = $request->validate($rules);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_PERSONAL,
            'profile_completed_flg' => 0,
        ]);

        Auth::login($user);

        return redirect('/mypage');
    }
}


