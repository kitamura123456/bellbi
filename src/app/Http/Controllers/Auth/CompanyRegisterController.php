<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyRegisterController extends Controller
{
    /**
     * 事業者（店舗アカウント）登録フォーム表示
     *
     * 当面は公開フォームとしておき、運営判断で後から認証や招待制に切り替えられるようにする。
     */
    public function showForm()
    {
        $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
        return view('auth.company-register', compact('industryTypes'));
    }

    /**
     * 事業者（店舗アカウント）登録処理
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255'],
            'industry_type' => ['required', 'integer'],
            'business_category' => ['nullable', 'integer'],
        ]);

        // 事業者用ユーザを作成
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_COMPANY,
            'profile_completed_flg' => 0,
        ]);

        // 会社レコードを作成
        Company::create([
            'user_id' => $user->id,
            'name' => $data['company_name'],
            'industry_type' => $data['industry_type'],
            'business_category' => $data['business_category'] ?? null,
            'plan_id' => null,
            'plan_status' => 1,
            'delete_flg' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('company.dashboard');
    }
}


