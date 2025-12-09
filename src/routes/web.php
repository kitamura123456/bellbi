<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\CompanyRegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboard;
use App\Http\Controllers\Company\CompanyController as CompanyInfo;
use App\Http\Controllers\Company\StoreController as CompanyStores;
use App\Http\Controllers\Company\JobPostController as CompanyJobPosts;
use App\Http\Controllers\Company\JobApplicationController as CompanyApplications;
use App\Http\Controllers\Company\ScoutController as CompanyScouts;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Mypage\ScoutProfileController;
use App\Http\Controllers\Mypage\ScoutController as MypageScouts;
use App\Http\Controllers\Mypage\ReservationController as MypageReservations;
use App\Http\Controllers\Company\StaffController as CompanyStaffs;
use App\Http\Controllers\Company\MenuController as CompanyMenus;
use App\Http\Controllers\Company\ScheduleController as CompanySchedules;
use App\Http\Controllers\Company\ReservationController as CompanyReservations;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Api\BusinessCategoryController;

// API
Route::get('/api/business-categories/{industry_type}', [BusinessCategoryController::class, 'getCategories'])->name('api.business-categories');

// トップ・求人
Route::get('/', [JobPostController::class, 'index'])->name('home');
Route::get('/jobs', [JobPostController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobPostController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])
    ->name('jobs.apply');

// 予約
Route::get('/reservations/search', [ReservationController::class, 'search'])->name('reservations.search');
Route::get('/reservations/store/{store}', [ReservationController::class, 'store'])->name('reservations.store');
Route::post('/reservations/store/{store}/booking', [ReservationController::class, 'booking'])->name('reservations.booking');
Route::post('/reservations/store/{store}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');

// 認証
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// 事業者登録（店舗アカウント用）
Route::get('/company/register', [CompanyRegisterController::class, 'showForm'])->name('company.register');
Route::post('/company/register', [CompanyRegisterController::class, 'register'])->name('company.register.post');

// ロール別ダッシュボード（最低限のプレースホルダ）
Route::middleware('auth')->group(function () {
    // 個人用マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/scout-profile', [ScoutProfileController::class, 'edit'])->name('mypage.scout-profile.edit');
    Route::put('/mypage/scout-profile', [ScoutProfileController::class, 'update'])->name('mypage.scout-profile.update');
    Route::get('/mypage/scouts', [MypageScouts::class, 'index'])->name('mypage.scouts.index');
    Route::get('/mypage/scouts/{scout}', [MypageScouts::class, 'show'])->name('mypage.scouts.show');
    Route::post('/mypage/scouts/{scout}/reply', [MypageScouts::class, 'reply'])->name('mypage.scouts.reply');
    Route::get('/mypage/reservations', [MypageReservations::class, 'index'])->name('mypage.reservations.index');
    Route::get('/mypage/reservations/{reservation}', [MypageReservations::class, 'show'])->name('mypage.reservations.show');
    Route::post('/mypage/reservations/{reservation}/cancel', [MypageReservations::class, 'cancel'])->name('mypage.reservations.cancel');

    // 事業者管理画面
    Route::get('/company', [CompanyDashboard::class, 'index'])->name('company.dashboard');
    Route::get('/company/info', [CompanyInfo::class, 'show'])->name('company.info');
    Route::get('/company/info/edit', [CompanyInfo::class, 'edit'])->name('company.info.edit');
    Route::put('/company/info', [CompanyInfo::class, 'update'])->name('company.info.update');
    
    Route::get('/company/stores', [CompanyStores::class, 'index'])->name('company.stores.index');
    Route::get('/company/stores/create', [CompanyStores::class, 'create'])->name('company.stores.create');
    Route::post('/company/stores', [CompanyStores::class, 'store'])->name('company.stores.store');
    Route::get('/company/stores/{store}/edit', [CompanyStores::class, 'edit'])->name('company.stores.edit');
    Route::put('/company/stores/{store}', [CompanyStores::class, 'update'])->name('company.stores.update');
    Route::delete('/company/stores/{store}', [CompanyStores::class, 'destroy'])->name('company.stores.destroy');

    Route::get('/company/job-posts', [CompanyJobPosts::class, 'index'])->name('company.job-posts.index');
    Route::get('/company/job-posts/create', [CompanyJobPosts::class, 'create'])->name('company.job-posts.create');
    Route::post('/company/job-posts', [CompanyJobPosts::class, 'store'])->name('company.job-posts.store');
    Route::get('/company/job-posts/{jobPost}/edit', [CompanyJobPosts::class, 'edit'])->name('company.job-posts.edit');
    Route::put('/company/job-posts/{jobPost}', [CompanyJobPosts::class, 'update'])->name('company.job-posts.update');
    Route::delete('/company/job-posts/{jobPost}', [CompanyJobPosts::class, 'destroy'])->name('company.job-posts.destroy');

    Route::get('/company/applications', [CompanyApplications::class, 'index'])->name('company.applications.index');
    Route::get('/company/applications/{application}', [CompanyApplications::class, 'show'])->name('company.applications.show');
    Route::put('/company/applications/{application}/status', [CompanyApplications::class, 'updateStatus'])->name('company.applications.update-status');

    Route::get('/company/scouts/search', [CompanyScouts::class, 'search'])->name('company.scouts.search');
    Route::get('/company/scouts/create/{profile}', [CompanyScouts::class, 'create'])->name('company.scouts.create');
    Route::post('/company/scouts/{profile}', [CompanyScouts::class, 'store'])->name('company.scouts.store');
    Route::get('/company/scouts/sent', [CompanyScouts::class, 'sent'])->name('company.scouts.sent');
    Route::get('/company/scouts/{scout}', [CompanyScouts::class, 'show'])->name('company.scouts.show');

    Route::get('/company/staffs', [CompanyStaffs::class, 'index'])->name('company.staffs.index');
    Route::get('/company/staffs/create', [CompanyStaffs::class, 'create'])->name('company.staffs.create');
    Route::post('/company/staffs', [CompanyStaffs::class, 'store'])->name('company.staffs.store');
    Route::get('/company/staffs/{staff}/edit', [CompanyStaffs::class, 'edit'])->name('company.staffs.edit');
    Route::put('/company/staffs/{staff}', [CompanyStaffs::class, 'update'])->name('company.staffs.update');
    Route::delete('/company/staffs/{staff}', [CompanyStaffs::class, 'destroy'])->name('company.staffs.destroy');

    Route::get('/company/menus', [CompanyMenus::class, 'index'])->name('company.menus.index');
    Route::get('/company/menus/create', [CompanyMenus::class, 'create'])->name('company.menus.create');
    Route::post('/company/menus', [CompanyMenus::class, 'store'])->name('company.menus.store');
    Route::get('/company/menus/{menu}/edit', [CompanyMenus::class, 'edit'])->name('company.menus.edit');
    Route::put('/company/menus/{menu}', [CompanyMenus::class, 'update'])->name('company.menus.update');
    Route::delete('/company/menus/{menu}', [CompanyMenus::class, 'destroy'])->name('company.menus.destroy');

    Route::get('/company/schedules', [CompanySchedules::class, 'index'])->name('company.schedules.index');
    Route::get('/company/schedules/{store}/edit', [CompanySchedules::class, 'edit'])->name('company.schedules.edit');
    Route::put('/company/schedules/{store}', [CompanySchedules::class, 'update'])->name('company.schedules.update');

    Route::get('/company/reservations', [CompanyReservations::class, 'index'])->name('company.reservations.index');
    Route::get('/company/reservations/{reservation}', [CompanyReservations::class, 'show'])->name('company.reservations.show');
    Route::put('/company/reservations/{reservation}/status', [CompanyReservations::class, 'updateStatus'])->name('company.reservations.update-status');

    // 管理画面 - ダッシュボード
    Route::get('/admin', function () {
        return view('admin.index');
    })->name('admin.index');

    // 管理画面 - ユーザー管理
    Route::resource('admin/users', UserController::class, [
        'as' => 'admin',
        'names' => [
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]
    ]);
});
