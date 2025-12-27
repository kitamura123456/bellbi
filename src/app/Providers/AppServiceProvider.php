<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\JobApplication;
use App\Models\Reservation;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Companyレイアウト用のView Composer
        View::composer('layouts.company', function ($view) {
            $user = Auth::user();
            $company = $user?->company;
            
            if (!$company) {
                return;
            }
            
            // サイドバー用の最小限の統計情報のみ取得（未読の項目のみ）
            $sidebarStats = [
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
                    ->whereIn('status', [1, 2]) // 応募済、書類選考中
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
            
            $view->with('sidebarStats', $sidebarStats);
        });
    }
}
