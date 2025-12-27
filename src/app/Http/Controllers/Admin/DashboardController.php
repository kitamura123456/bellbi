<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Plan;
use App\Models\Subsidy;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 今月の開始日と終了日
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // 先月の開始日と終了日
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // 総ユーザー数
        $totalUsersCount = User::where('delete_flg', 0)->count();
        $thisMonthUsersCount = User::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthUsersCount = User::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // 総事業者数
        $totalCompaniesCount = Company::where('delete_flg', 0)->count();
        $thisMonthCompaniesCount = Company::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthCompaniesCount = Company::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // 総求人数
        $totalJobPostsCount = JobPost::where('delete_flg', 0)->count();
        $activeJobPostsCount = JobPost::where('delete_flg', 0)
            ->where('status', 1)
            ->count();
        $thisMonthJobPostsCount = JobPost::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthJobPostsCount = JobPost::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // 総応募数
        $totalApplicationsCount = JobApplication::where('delete_flg', 0)->count();
        $thisMonthApplicationsCount = JobApplication::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthApplicationsCount = JobApplication::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // 総注文数
        $totalOrdersCount = Order::where('delete_flg', 0)->count();
        $thisMonthOrdersCount = Order::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthOrdersCount = Order::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // 今月の売上（EC売上のみ）
        $thisMonthRevenue = Order::where('delete_flg', 0)
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // 先月の売上
        $lastMonthRevenue = Order::where('delete_flg', 0)
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');

        // 総予約数
        $totalReservationsCount = Reservation::where('delete_flg', 0)->count();
        $thisMonthReservationsCount = Reservation::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $lastMonthReservationsCount = Reservation::where('delete_flg', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // 過去30日間の時系列データを取得
        $days = 30;
        $chartData = $this->getChartData($days);

        $stats = [
            'totalUsersCount' => $totalUsersCount,
            'thisMonthUsersCount' => $thisMonthUsersCount,
            'lastMonthUsersCount' => $lastMonthUsersCount,
            'totalCompaniesCount' => $totalCompaniesCount,
            'thisMonthCompaniesCount' => $thisMonthCompaniesCount,
            'lastMonthCompaniesCount' => $lastMonthCompaniesCount,
            'totalJobPostsCount' => $totalJobPostsCount,
            'activeJobPostsCount' => $activeJobPostsCount,
            'thisMonthJobPostsCount' => $thisMonthJobPostsCount,
            'lastMonthJobPostsCount' => $lastMonthJobPostsCount,
            'totalApplicationsCount' => $totalApplicationsCount,
            'thisMonthApplicationsCount' => $thisMonthApplicationsCount,
            'lastMonthApplicationsCount' => $lastMonthApplicationsCount,
            'totalOrdersCount' => $totalOrdersCount,
            'thisMonthOrdersCount' => $thisMonthOrdersCount,
            'lastMonthOrdersCount' => $lastMonthOrdersCount,
            'thisMonthRevenue' => $thisMonthRevenue,
            'lastMonthRevenue' => $lastMonthRevenue,
            'totalReservationsCount' => $totalReservationsCount,
            'thisMonthReservationsCount' => $thisMonthReservationsCount,
            'lastMonthReservationsCount' => $lastMonthReservationsCount,
            'chartData' => $chartData,
        ];

        return view('admin.index', compact('stats'));
    }

    /**
     * グラフ用の時系列データを取得
     */
    private function getChartData($days = 30)
    {
        $labels = [];
        $usersData = [];
        $companiesData = [];
        $jobPostsData = [];
        $applicationsData = [];
        $ordersData = [];
        $revenueData = [];

        // 過去30日間の日付を生成
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('m/d');

            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            // その日の新規ユーザー数
            $usersData[] = User::where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の新規事業者数
            $companiesData[] = Company::where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の新規求人数
            $jobPostsData[] = JobPost::where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の新規応募数
            $applicationsData[] = JobApplication::where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の新規注文数
            $ordersData[] = Order::where('delete_flg', 0)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            // その日の売上
            $revenueData[] = Order::where('delete_flg', 0)
                ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('total_amount');
        }

        return [
            'labels' => $labels,
            'users' => $usersData,
            'companies' => $companiesData,
            'jobPosts' => $jobPostsData,
            'applications' => $applicationsData,
            'orders' => $ordersData,
            'revenue' => $revenueData,
        ];
    }
}

