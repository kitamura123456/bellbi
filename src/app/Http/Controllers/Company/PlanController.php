<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;

class PlanController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

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
     * Stripeサブスクリプションセッション作成
     */
    public function createStripeSubscriptionSession(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        try {
            // Stripe Customerを作成または取得
            $stripeCustomerId = $company->stripe_customer_id;
            if (!$stripeCustomerId) {
                $stripeCustomer = Customer::create([
                    'email' => $user->email,
                    'name' => $company->name,
                    'metadata' => [
                        'company_id' => $company->id,
                    ],
                ]);
                $stripeCustomerId = $stripeCustomer->id;
                $company->stripe_customer_id = $stripeCustomerId;
                $company->save();
            }

            // Stripe Checkoutセッションを作成（サブスクリプション用）
            $checkoutSession = StripeSession::create([
                'customer' => $stripeCustomerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $plan->name,
                        ],
                        'unit_amount' => $plan->price_monthly,
                        'recurring' => [
                            'interval' => 'month',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('company.plans.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('company.plans.show', $plan) . '?canceled=1',
                'metadata' => [
                    'company_id' => $company->id,
                    'plan_id' => $plan->id,
                ],
            ]);

            // セッションIDを一時保存
            Session::put('pending_subscription', [
                'plan_id' => $plan->id,
                'session_id' => $checkoutSession->id,
            ]);

            return redirect($checkoutSession->url);
        } catch (ApiErrorException $e) {
            return redirect()->route('company.plans.show', $plan)
                ->with('error', '決済セッションの作成に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * Stripeサブスクリプション成功時の処理
     */
    public function stripeSuccess(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('error', '会社情報が登録されていません。');
        }

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('company.plans.index')->with('error', 'セッションIDが無効です。');
        }

        try {
            $checkoutSession = StripeSession::retrieve($sessionId, [
                'expand' => ['subscription'],
            ]);

            if ($checkoutSession->mode !== 'subscription') {
                return redirect()->route('company.plans.index')
                    ->with('error', 'サブスクリプション情報が無効です。');
            }

            // subscriptionが展開されているか確認
            $stripeSubscriptionId = null;
            if (is_string($checkoutSession->subscription)) {
                // subscriptionがID文字列の場合、取得する
                $stripeSubscriptionId = $checkoutSession->subscription;
                $stripeSubscription = StripeSubscription::retrieve($stripeSubscriptionId);
            } elseif (is_object($checkoutSession->subscription)) {
                // subscriptionがオブジェクトの場合
                $stripeSubscription = $checkoutSession->subscription;
            } else {
                return redirect()->route('company.plans.index')
                    ->with('error', 'サブスクリプション情報が無効です。');
            }

            // metadataからplan_idを取得
            $planId = null;
            if (is_object($checkoutSession->metadata) && isset($checkoutSession->metadata->plan_id)) {
                $planId = $checkoutSession->metadata->plan_id;
            } elseif (is_array($checkoutSession->metadata) && isset($checkoutSession->metadata['plan_id'])) {
                $planId = $checkoutSession->metadata['plan_id'];
            }

            if (!$planId) {
                $pendingData = Session::get('pending_subscription');
                $planId = $pendingData['plan_id'] ?? null;
            }

            if (!$planId) {
                return redirect()->route('company.plans.index')
                    ->with('error', 'プラン情報が見つかりません。');
            }

            $plan = Plan::find($planId);
            if (!$plan) {
                return redirect()->route('company.plans.index')
                    ->with('error', 'プランが見つかりません。');
            }

            DB::beginTransaction();
            try {
                // 既存の有効な契約を解約
                $existingSubscriptions = Subscription::where('company_id', $company->id)
                    ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIAL])
                    ->where('delete_flg', 0)
                    ->get();

                foreach ($existingSubscriptions as $existing) {
                    // Stripe側のサブスクリプションもキャンセル
                    if ($existing->stripe_subscription_id) {
                        try {
                            $stripeSub = StripeSubscription::retrieve($existing->stripe_subscription_id);
                            $stripeSub->cancel();
                        } catch (\Exception $e) {
                            // エラーは無視（既にキャンセルされている可能性）
                        }
                    }
                    $existing->status = Subscription::STATUS_CANCELLED;
                    $existing->ended_at = Carbon::now();
                    $existing->save();
                }

                // 新しい契約を作成
                $subscription = new Subscription();
                $subscription->company_id = $company->id;
                $subscription->plan_id = $plan->id;
                $subscription->stripe_subscription_id = $stripeSubscription->id;
                $subscription->stripe_customer_id = is_string($stripeSubscription->customer) 
                    ? $stripeSubscription->customer 
                    : ($stripeSubscription->customer->id ?? null);
                $subscription->status = Subscription::STATUS_ACTIVE;
                
                // current_period_startとcurrent_period_endの処理
                $currentPeriodStart = $stripeSubscription->current_period_start ?? time();
                $currentPeriodEnd = $stripeSubscription->current_period_end ?? (time() + 30 * 24 * 60 * 60); // デフォルトは30日後
                
                $subscription->started_at = Carbon::createFromTimestamp($currentPeriodStart);
                $subscription->ended_at = null;
                $subscription->next_billing_at = Carbon::createFromTimestamp($currentPeriodEnd);
                $subscription->delete_flg = 0;
                $subscription->save();

                // Companyテーブルを更新
                $company->plan_id = $plan->id;
                $company->plan_status = Subscription::STATUS_ACTIVE;
                if (!$company->stripe_customer_id) {
                    $company->stripe_customer_id = $stripeSubscription->customer;
                }
                $company->save();

                DB::commit();

                Session::forget('pending_subscription');

                return redirect()->route('company.plans.index')
                    ->with('status', 'プランの契約が完了しました。');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('company.plans.index')
                    ->with('error', '契約処理中にエラーが発生しました: ' . $e->getMessage());
            }
        } catch (ApiErrorException $e) {
            return redirect()->route('company.plans.index')
                ->with('error', '決済情報の取得に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * プラン契約処理（従来の方法 - 決済なし）
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

            // Stripe側のサブスクリプションもキャンセル
            if ($activeSubscription->stripe_subscription_id) {
                try {
                    $stripeSubscription = StripeSubscription::retrieve($activeSubscription->stripe_subscription_id);
                    $stripeSubscription->cancel();
                } catch (ApiErrorException $e) {
                    // エラーは無視（既にキャンセルされている可能性）
                }
            }

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

