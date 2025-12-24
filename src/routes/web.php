<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\CompanyRegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanies;
use App\Http\Controllers\Admin\JobPostController as AdminJobPosts;
use App\Http\Controllers\Admin\PlanController as AdminPlans;
use App\Http\Controllers\Admin\SubsidyController as AdminSubsidies;
use App\Http\Controllers\Admin\SystemSettingController as AdminSystemSettings;
use App\Http\Controllers\Company\DashboardController as CompanyDashboard;
use App\Http\Controllers\Company\CompanyController as CompanyInfo;
use App\Http\Controllers\Company\StoreController as CompanyStores;
use App\Http\Controllers\Company\JobPostController as CompanyJobPosts;
use App\Http\Controllers\Company\JobApplicationController as CompanyApplications;
use App\Http\Controllers\Company\ScoutController as CompanyScouts;
use App\Http\Controllers\Company\MessageController as CompanyMessages;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Mypage\ScoutProfileController;
use App\Http\Controllers\Mypage\ScoutController as MypageScouts;
use App\Http\Controllers\Mypage\ReservationController as MypageReservations;
use App\Http\Controllers\Mypage\MessageController as MypageMessages;
use App\Http\Controllers\Company\StaffController as CompanyStaffs;
use App\Http\Controllers\Company\MenuController as CompanyMenus;
use App\Http\Controllers\Company\ScheduleController as CompanySchedules;
use App\Http\Controllers\Company\ReservationController as CompanyReservations;
use App\Http\Controllers\Company\AccountItemController as CompanyAccountItems;
use App\Http\Controllers\Company\TransactionController as CompanyTransactions;
use App\Http\Controllers\Company\PlanController as CompanyPlans;
use App\Http\Controllers\Company\SubsidyController as CompanySubsidies;
use App\Http\Controllers\Company\ShopController as CompanyShops;
use App\Http\Controllers\Company\ProductController as CompanyProducts;
use App\Http\Controllers\Company\OrderController as CompanyOrders;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Api\BusinessCategoryController;

// API
Route::get('/api/business-categories/{industry_type}', [BusinessCategoryController::class, 'getCategories'])->name('api.business-categories');
Route::get('/api/cities/by-prefecture', [\App\Http\Controllers\Api\CityController::class, 'getCitiesByPrefecture'])->name('api.cities.by-prefecture');
Route::post('/api/cities/by-location', [\App\Http\Controllers\Api\CityController::class, 'getCitiesByLocation'])->name('api.cities.by-location');

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

// ECショップ（エンドユーザ向け）
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/product/{product}', [ShopController::class, 'show'])->name('shops.show');

// カート機能
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

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
    
    // 注文機能
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::get('/orders/complete/{order}', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/mypage/orders', [OrderController::class, 'index'])->name('mypage.orders.index');
    Route::get('/mypage/orders/{order}', [OrderController::class, 'show'])->name('mypage.orders.show');
    
    // メッセージ機能
    Route::get('/mypage/messages', [MypageMessages::class, 'index'])->name('mypage.messages.index');
    Route::get('/mypage/messages/{conversation}', [MypageMessages::class, 'show'])->name('mypage.messages.show');
    Route::post('/mypage/messages/{conversation}', [MypageMessages::class, 'store'])->name('mypage.messages.store');
    Route::get('/mypage/applications/{application}/messages', [MypageMessages::class, 'createFromApplication'])->name('mypage.messages.create-from-application');
    Route::get('/mypage/scouts/{scout}/messages', [MypageMessages::class, 'createFromScout'])->name('mypage.messages.create-from-scout');

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
    
    // メッセージ機能
    Route::get('/company/messages', [CompanyMessages::class, 'index'])->name('company.messages.index');
    Route::get('/company/messages/{conversation}', [CompanyMessages::class, 'show'])->name('company.messages.show');
    Route::post('/company/messages/{conversation}', [CompanyMessages::class, 'store'])->name('company.messages.store');
    Route::get('/company/applications/{application}/messages', [CompanyMessages::class, 'createFromApplication'])->name('company.messages.create-from-application');
    Route::get('/company/scouts/{scout}/messages', [CompanyMessages::class, 'createFromScout'])->name('company.messages.create-from-scout');

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

    // 経営現場改善機能（会計）
    Route::get('/company/account-items', [CompanyAccountItems::class, 'index'])->name('company.account-items.index');
    Route::get('/company/account-items/create', [CompanyAccountItems::class, 'create'])->name('company.account-items.create');
    Route::post('/company/account-items', [CompanyAccountItems::class, 'store'])->name('company.account-items.store');
    Route::get('/company/account-items/{accountItem}/edit', [CompanyAccountItems::class, 'edit'])->name('company.account-items.edit');
    Route::put('/company/account-items/{accountItem}', [CompanyAccountItems::class, 'update'])->name('company.account-items.update');
    Route::delete('/company/account-items/{accountItem}', [CompanyAccountItems::class, 'destroy'])->name('company.account-items.destroy');

    Route::get('/company/transactions', [CompanyTransactions::class, 'index'])->name('company.transactions.index');
    Route::get('/company/transactions/create', [CompanyTransactions::class, 'create'])->name('company.transactions.create');
    Route::post('/company/transactions', [CompanyTransactions::class, 'store'])->name('company.transactions.store');
    Route::get('/company/transactions/{transaction}/edit', [CompanyTransactions::class, 'edit'])->name('company.transactions.edit');
    Route::put('/company/transactions/{transaction}', [CompanyTransactions::class, 'update'])->name('company.transactions.update');
    Route::delete('/company/transactions/{transaction}', [CompanyTransactions::class, 'destroy'])->name('company.transactions.destroy');
    Route::get('/company/transactions/report', [CompanyTransactions::class, 'report'])->name('company.transactions.report');
    Route::get('/company/transactions/export', [CompanyTransactions::class, 'export'])->name('company.transactions.export');

    // プラン・課金管理
    Route::get('/company/plans', [CompanyPlans::class, 'index'])->name('company.plans.index');
    Route::get('/company/plans/{plan}', [CompanyPlans::class, 'show'])->name('company.plans.show');
    Route::post('/company/plans/{plan}/subscribe', [CompanyPlans::class, 'subscribe'])->name('company.plans.subscribe');
    Route::get('/company/plans/{plan}/change', [CompanyPlans::class, 'change'])->name('company.plans.change');
    Route::post('/company/plans/{plan}/update', [CompanyPlans::class, 'update'])->name('company.plans.update');
    Route::post('/company/plans/cancel', [CompanyPlans::class, 'cancel'])->name('company.plans.cancel');

    // 補助金情報機能
    Route::get('/company/subsidies', [CompanySubsidies::class, 'index'])->name('company.subsidies.index');
    Route::get('/company/subsidies/{subsidy}', [CompanySubsidies::class, 'show'])->name('company.subsidies.show');

    // ECモール機能
    Route::get('/company/shops', [CompanyShops::class, 'index'])->name('company.shops.index');
    Route::get('/company/shops/create', [CompanyShops::class, 'create'])->name('company.shops.create');
    Route::post('/company/shops', [CompanyShops::class, 'store'])->name('company.shops.store');
    Route::get('/company/shops/{shop}/edit', [CompanyShops::class, 'edit'])->name('company.shops.edit');
    Route::put('/company/shops/{shop}', [CompanyShops::class, 'update'])->name('company.shops.update');
    Route::delete('/company/shops/{shop}', [CompanyShops::class, 'destroy'])->name('company.shops.destroy');

    Route::get('/company/products', [CompanyProducts::class, 'index'])->name('company.products.index');
    Route::get('/company/products/create', [CompanyProducts::class, 'create'])->name('company.products.create');
    Route::post('/company/products', [CompanyProducts::class, 'store'])->name('company.products.store');
    Route::get('/company/products/{product}/edit', [CompanyProducts::class, 'edit'])->name('company.products.edit');
    Route::put('/company/products/{product}', [CompanyProducts::class, 'update'])->name('company.products.update');
    Route::delete('/company/products/{product}', [CompanyProducts::class, 'destroy'])->name('company.products.destroy');

    Route::get('/company/orders', [CompanyOrders::class, 'index'])->name('company.orders.index');
    Route::get('/company/orders/{order}', [CompanyOrders::class, 'show'])->name('company.orders.show');
    Route::put('/company/orders/{order}/status', [CompanyOrders::class, 'updateStatus'])->name('company.orders.update-status');

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

    // 管理画面 - 事業者管理
    Route::resource('admin/companies', AdminCompanies::class, [
        'as' => 'admin',
        'names' => [
            'index' => 'admin.companies.index',
            'edit' => 'admin.companies.edit',
            'update' => 'admin.companies.update',
            'destroy' => 'admin.companies.destroy',
        ],
        'except' => ['create', 'show', 'store']
    ]);

    // 管理画面 - 求人管理
    Route::resource('admin/job-posts', AdminJobPosts::class, [
        'as' => 'admin',
        'names' => [
            'index' => 'admin.job-posts.index',
            'edit' => 'admin.job-posts.edit',
            'update' => 'admin.job-posts.update',
            'destroy' => 'admin.job-posts.destroy',
        ],
        'except' => ['create', 'show', 'store']
    ]);

    // 管理画面 - プラン管理
    Route::resource('admin/plans', AdminPlans::class, [
        'as' => 'admin',
        'names' => [
            'index' => 'admin.plans.index',
            'create' => 'admin.plans.create',
            'store' => 'admin.plans.store',
            'edit' => 'admin.plans.edit',
            'update' => 'admin.plans.update',
            'destroy' => 'admin.plans.destroy',
        ]
    ]);

    // 管理画面 - 補助金情報管理
    Route::resource('admin/subsidies', AdminSubsidies::class, [
        'as' => 'admin',
        'names' => [
            'index' => 'admin.subsidies.index',
            'create' => 'admin.subsidies.create',
            'store' => 'admin.subsidies.store',
            'edit' => 'admin.subsidies.edit',
            'update' => 'admin.subsidies.update',
            'destroy' => 'admin.subsidies.destroy',
        ]
    ]);

    // 管理画面 - システム設定
    Route::get('/admin/system-settings', [AdminSystemSettings::class, 'index'])->name('admin.system-settings.index');
    Route::put('/admin/system-settings', [AdminSystemSettings::class, 'update'])->name('admin.system-settings.update');
});
