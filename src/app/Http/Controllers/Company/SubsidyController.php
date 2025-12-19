<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Subsidy;
use App\Enums\Todofuken;
use App\Services\BusinessCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubsidyController extends Controller
{
    /**
     * 補助金一覧画面
     */
    public function index(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        // 検索条件
        $category = $request->input('category');
        $targetRegion = $request->input('target_region');
        $status = $request->input('status');
        $keyword = $request->input('keyword');

        $query = Subsidy::active()
            ->orderBy('application_start_at', 'desc')
            ->orderBy('created_at', 'desc');

        // 業種フィルター（事業者の業種に合致するもの、または全業種対象）
        if ($company->industry_type) {
            $query->where(function($q) use ($company) {
                $q->whereNull('applicable_industry_type')
                  ->orWhere('applicable_industry_type', $company->industry_type);
            });
        }

        // カテゴリフィルター
        if ($category) {
            $query->where('category', $category);
        }

        // 地域フィルター（対象地域がNULL（全国）または指定地域）
        if ($targetRegion) {
            $query->where(function($q) use ($targetRegion) {
                $q->whereNull('target_region')
                  ->orWhere('target_region', $targetRegion);
            });
        }

        // ステータスフィルター
        if ($status) {
            $query->where('status', $status);
        }

        // キーワード検索
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('summary', 'like', '%' . $keyword . '%');
            });
        }

        $subsidies = $query->paginate(20);

        // 都道府県リスト
        $prefectures = Todofuken::cases();

        return view('company.subsidies.index', compact(
            'subsidies',
            'prefectures',
            'category',
            'targetRegion',
            'status',
            'keyword'
        ));
    }

    /**
     * 補助金詳細画面
     */
    public function show($id)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $subsidy = Subsidy::active()->findOrFail($id);

        // 事業者の業種に合致するか確認（全業種対象の場合は表示）
        if ($subsidy->applicable_industry_type && 
            $subsidy->applicable_industry_type !== $company->industry_type) {
            return redirect()->route('company.subsidies.index')
                ->with('error', 'この補助金は対象外です。');
        }

        return view('company.subsidies.show', compact('subsidy'));
    }
}
