<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\JobApplication;
use App\Models\Transaction;
use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return view('company.index', compact('user', 'company'));
        }

        // 今月の開始日と終了日
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // 先月の開始日と終了日
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // 今月の売上（Transactionから）
        $thisMonthRevenue = Transaction::where('company_id', $company->id)
            ->where('transaction_type', Transaction::TYPE_REVENUE)
            ->where('delete_flg', 0)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('amount + tax_amount'));

        // 先月の売上
        $lastMonthRevenue = Transaction::where('company_id', $company->id)
            ->where('transaction_type', Transaction::TYPE_REVENUE)
            ->where('delete_flg', 0)
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum(DB::raw('amount + tax_amount'));

        // 今月のEC売上（Orderから）
        $thisMonthECRevenue = Order::whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)->where('delete_flg', 0);
            })
            ->where('delete_flg', 0)
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // 先月のEC売上
        $lastMonthECRevenue = Order::whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)->where('delete_flg', 0);
            })
            ->where('delete_flg', 0)
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');

        // 合計売上（Transaction + EC）
        $thisMonthTotalRevenue = $thisMonthRevenue + $thisMonthECRevenue;
        $lastMonthTotalRevenue = $lastMonthRevenue + $lastMonthECRevenue;

        // 新しい注文数（未処理：新規、入金確認済）
        $newOrdersCount = Order::whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)->where('delete_flg', 0);
            })
            ->where('delete_flg', 0)
            ->whereIn('status', [Order::STATUS_NEW, Order::STATUS_PAID])
            ->count();

        // 今月の注文数
        $thisMonthOrdersCount = Order::whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)->where('delete_flg', 0);
            })
            ->where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 新しい求人応募数（未確認：応募済、書類選考中）
        $newApplicationsCount = JobApplication::whereHas('jobPost', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->where('delete_flg', 0)
            ->whereIn('status', [1, 2]) // 応募済、書類選考中
            ->count();

        // 今月の応募数
        $thisMonthApplicationsCount = JobApplication::whereHas('jobPost', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 新しい予約数（今後の予約）
        $upcomingReservationsCount = Reservation::whereHas('store', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->where('delete_flg', 0)
            ->where('reservation_date', '>=', Carbon::today())
            ->count();

        // アクティブな求人数
        $activeJobPostsCount = $company->jobPosts()
            ->where('delete_flg', 0)
            ->where('status', 1) // 公開中
            ->count();

        // アクティブな店舗数
        $activeStoresCount = $company->stores()
            ->where('delete_flg', 0)
            ->count();

        // アクティブなECショップ数
        $activeShopsCount = $company->shops()
            ->where('delete_flg', 0)
            ->where('status', Shop::STATUS_PUBLIC)
            ->count();

        // 過去30日間の時系列データを取得
        $days = 30;
        $chartData = $this->getChartData($company, $days);

        $stats = [
            'thisMonthTotalRevenue' => $thisMonthTotalRevenue,
            'lastMonthTotalRevenue' => $lastMonthTotalRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'thisMonthECRevenue' => $thisMonthECRevenue,
            'newOrdersCount' => $newOrdersCount,
            'thisMonthOrdersCount' => $thisMonthOrdersCount,
            'newApplicationsCount' => $newApplicationsCount,
            'thisMonthApplicationsCount' => $thisMonthApplicationsCount,
            'upcomingReservationsCount' => $upcomingReservationsCount,
            'activeJobPostsCount' => $activeJobPostsCount,
            'activeStoresCount' => $activeStoresCount,
            'activeShopsCount' => $activeShopsCount,
            'chartData' => $chartData,
        ];

        return view('company.index', compact('user', 'company', 'stats'));
    }

    /**
     * グラフ用の時系列データを取得
     */
    private function getChartData($company, $days = 30)
    {
        $labels = [];
        $revenueData = [];
        $ordersData = [];
        $applicationsData = [];
        $reservationsData = [];

        // 過去30日間の日付を生成
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('m/d');

            // その日の売上（Transaction + EC）
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $transactionRevenue = Transaction::where('company_id', $company->id)
                ->where('transaction_type', Transaction::TYPE_REVENUE)
                ->where('delete_flg', 0)
                ->whereBetween('date', [$dayStart, $dayEnd])
                ->sum(DB::raw('amount + tax_amount'));

            $ecRevenue = Order::whereHas('shop', function($q) use ($company) {
                    $q->where('company_id', $company->id)->where('delete_flg', 0);
                })
                ->where('delete_flg', 0)
                ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('total_amount');

            $revenueData[] = $transactionRevenue + $ecRevenue;

            // その日の注文数
            $ordersData[] = Order::whereHas('shop', function($q) use ($company) {
                    $q->where('company_id', $company->id)->where('delete_flg', 0);
                })
                ->where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の応募数
            $applicationsData[] = JobApplication::whereHas('jobPost', function($q) use ($company) {
                    $q->where('company_id', $company->id);
                })
                ->where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の予約数
            $reservationsData[] = Reservation::whereHas('store', function($q) use ($company) {
                    $q->where('company_id', $company->id);
                })
                ->where('delete_flg', 0)
                ->where('reservation_date', $dateStr)
                ->count();
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
            'applications' => $applicationsData,
            'reservations' => $reservationsData,
        ];
    }

    /**
     * サイドバーのバッジ用の統計情報を取得
     */
    public function getSidebarStats()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'newOrdersCount' => Order::whereHas('shop', function($q) use ($company) {
                    $q->where('company_id', $company->id)->where('delete_flg', 0);
                })
                ->where('delete_flg', 0)
                ->whereIn('status', [Order::STATUS_NEW, Order::STATUS_PAID])
                ->whereNull('viewed_at')
                ->count(),
            
            'newApplicationsCount' => JobApplication::whereHas('jobPost', function($q) use ($company) {
                    $q->where('company_id', $company->id);
                })
                ->where('delete_flg', 0)
                ->whereIn('status', [1, 2])
                ->whereNull('viewed_at')
                ->count(),
            
            'upcomingReservationsCount' => Reservation::whereHas('store', function($q) use ($company) {
                    $q->where('company_id', $company->id);
                })
                ->where('delete_flg', 0)
                ->where('reservation_date', '>=', Carbon::today())
                ->whereNull('viewed_at')
                ->count(),
        ];

        return response()->json($stats);
    }
}

