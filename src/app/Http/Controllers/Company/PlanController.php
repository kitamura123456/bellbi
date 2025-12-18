<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlanController extends Controller
{
    /**
     * プラン一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $plans = Plan::active()->orderBy('price_monthly')->get();
        $activeSubscription = $company->activeSubscription();

        return view('company.plans.index', compact('plans', 'activeSubscription', 'company'));
    }

    /**
     * プラン選択・契約画面
     */
    public function show(Plan $plan)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $activeSubscription = $company->activeSubscription();

        return view('company.plans.show', compact('plan', 'activeSubscription', 'company'));
    }

    /**
     * プラン契約処理
     */
    public function subscribe(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:1,2'], // 1:有効, 2:トライアル
        ]);

        try {
            DB::beginTransaction();

            // 既存の有効な契約を解約（論理削除）
            $existingSubscriptions = Subscription::where('company_id', $company->id)
                ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIAL])
                ->where('delete_flg', 0)
                ->get();

            foreach ($existingSubscriptions as $existing) {
                $existing->status = Subscription::STATUS_CANCELLED;
                $existing->ended_at = Carbon::now();
                $existing->save();
            }

            // 新しい契約を作成
            $subscription = new Subscription();
            $subscription->company_id = $company->id;
            $subscription->plan_id = $plan->id;
            $subscription->status = (int)$validated['status'];
            $subscription->started_at = Carbon::now();
            $subscription->ended_at = null; // 自動更新される想定
            $subscription->next_billing_at = Carbon::now()->addMonth(); // 1ヶ月後
            $subscription->delete_flg = 0;
            $subscription->save();

            // Companyテーブルのplan_idとplan_statusも更新
            $company->plan_id = $plan->id;
            $company->plan_status = (int)$validated['status'];
            $company->save();

            DB::commit();

            return redirect()->route('company.plans.index')
                ->with('status', 'プランを契約しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'プランの契約に失敗しました。もう一度お試しください。');
        }
    }

    /**
     * プラン変更画面
     */
    public function change(Plan $plan)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $activeSubscription = $company->activeSubscription();

        if (!$activeSubscription) {
            return redirect()->route('company.plans.index')
                ->with('error', '現在有効な契約がありません。');
        }

        return view('company.plans.change', compact('plan', 'activeSubscription', 'company'));
    }

    /**
     * プラン変更処理
     */
    public function update(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $activeSubscription = $company->activeSubscription();

        if (!$activeSubscription) {
            return redirect()->route('company.plans.index')
                ->with('error', '現在有効な契約がありません。');
        }

        try {
            DB::beginTransaction();

            // 既存の契約を解約
            $activeSubscription->status = Subscription::STATUS_CANCELLED;
            $activeSubscription->ended_at = Carbon::now();
            $activeSubscription->save();

            // 新しい契約を作成（既存のステータスを引き継ぐ）
            $newSubscription = new Subscription();
            $newSubscription->company_id = $company->id;
            $newSubscription->plan_id = $plan->id;
            $newSubscription->status = $activeSubscription->status; // 既存のステータスを引き継ぐ
            $newSubscription->started_at = Carbon::now();
            $newSubscription->ended_at = null;
            $newSubscription->next_billing_at = Carbon::now()->addMonth();
            $newSubscription->delete_flg = 0;
            $newSubscription->save();

            // Companyテーブルを更新
            $company->plan_id = $plan->id;
            $company->save();

            DB::commit();

            return redirect()->route('company.plans.index')
                ->with('status', 'プランを変更しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'プランの変更に失敗しました。もう一度お試しください。');
        }
    }

    /**
     * 契約解約処理
     */
    public function cancel()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $activeSubscription = $company->activeSubscription();

        if (!$activeSubscription) {
            return redirect()->route('company.plans.index')
                ->with('error', '現在有効な契約がありません。');
        }

        try {
            DB::beginTransaction();

            $activeSubscription->status = Subscription::STATUS_CANCELLED;
            $activeSubscription->ended_at = Carbon::now();
            $activeSubscription->save();

            // Companyテーブルのplan_statusを更新
            $company->plan_status = Subscription::STATUS_CANCELLED;
            $company->save();

            DB::commit();

            return redirect()->route('company.plans.index')
                ->with('status', 'プランを解約しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'プランの解約に失敗しました。もう一度お試しください。');
        }
    }
}

