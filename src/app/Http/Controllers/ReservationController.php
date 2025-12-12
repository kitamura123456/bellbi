<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\ServiceMenu;
use App\Models\Reservation;
use App\Models\ReservationMenu;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    // 店舗検索
    public function search(Request $request)
    {
        $query = Store::where('delete_flg', 0)
            ->where('accepts_reservations', 1)
            ->with('company');

        // エリアフィルタ（将来拡張）
        // if ($request->filled('area')) { ... }

        $stores = $query->paginate(20);

        return view('', compact('stores'));
    }

    // 店舗詳細・メニュー選択
    public function store(Store $store)
    {
        if (!$store->accepts_reservations) {
            abort(404);
        }

        $menus = $store->serviceMenus()
            ->where('is_active', 1)
            ->where('delete_flg', 0)
            ->orderBy('display_order')
            ->get();

        $staffs = $store->staffs()
            ->where('is_active', 1)
            ->where('delete_flg', 0)
            ->orderBy('display_order')
            ->get();

        return view('reservations.store', compact('store', 'menus', 'staffs'));
    }

    // 予約フォーム（日時選択）
    public function booking(Request $request, Store $store)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', '予約にはログインが必要です。');
        }

        if (!$store->accepts_reservations) {
            abort(404);
        }

        $validated = $request->validate([
            'menu_ids' => ['required', 'array', 'min:1'],
            'menu_ids.*' => ['exists:service_menus,id'],
            'staff_id' => ['nullable', 'exists:store_staffs,id'],
        ]);

        $menus = ServiceMenu::whereIn('id', $validated['menu_ids'])
            ->where('store_id', $store->id)
            ->where('is_active', 1)
            ->where('delete_flg', 0)
            ->get();

        if ($menus->isEmpty()) {
            return back()->with('error', 'メニューを選択してください。');
        }

        $totalDuration = $menus->sum('duration_minutes');
        $totalPrice = $menus->sum('price');

        $staffId = $validated['staff_id'] ?? null;
        $staff = $staffId ? $store->staffs()->find($staffId) : null;

        // 今日から2週間分の日付と空き枠を取得
        $availableDates = [];
        for ($i = 0; $i < 14; $i++) {
            $date = now()->addDays($i)->toDateString();
            $slots = $this->reservationService->getAvailableSlots($store, $date, $totalDuration, $staffId);
            if (!empty($slots)) {
                $availableDates[$date] = $slots;
            }
        }

        return view('reservations.booking', compact('store', 'menus', 'totalDuration', 'totalPrice', 'staff', 'availableDates'));
    }

    // 予約確定
    public function confirm(Request $request, Store $store)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$store->accepts_reservations) {
            abort(404);
        }

        $validated = $request->validate([
            'menu_ids' => ['required', 'array', 'min:1'],
            'menu_ids.*' => ['exists:service_menus,id'],
            'staff_id' => ['nullable', 'exists:store_staffs,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'customer_note' => ['nullable', 'string', 'max:500'],
        ]);

        $menus = ServiceMenu::whereIn('id', $validated['menu_ids'])
            ->where('store_id', $store->id)
            ->where('is_active', 1)
            ->where('delete_flg', 0)
            ->get();

        if ($menus->isEmpty()) {
            return back()->with('error', 'メニューが選択されていません。');
        }

        $totalDuration = $menus->sum('duration_minutes');
        $totalPrice = $menus->sum('price');

        $startTime = Carbon::parse($validated['reservation_date'] . ' ' . $validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($totalDuration);

        try {
            DB::transaction(function () use ($store, $validated, $menus, $totalDuration, $totalPrice, $startTime, $endTime) {
                // 予約作成
                $reservation = Reservation::create([
                    'store_id' => $store->id,
                    'user_id' => Auth::id(),
                    'staff_id' => $validated['staff_id'] ?? null,
                    'reservation_date' => $validated['reservation_date'],
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'total_duration_minutes' => $totalDuration,
                    'total_price' => $totalPrice,
                    'status' => Reservation::STATUS_CONFIRMED,
                    'customer_note' => $validated['customer_note'] ?? null,
                    'delete_flg' => 0,
                ]);

                // 予約メニュー作成
                foreach ($menus as $index => $menu) {
                    ReservationMenu::create([
                        'reservation_id' => $reservation->id,
                        'service_menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'duration_minutes' => $menu->duration_minutes,
                        'price' => $menu->price,
                        'display_order' => $index,
                        'delete_flg' => 0,
                    ]);
                }
            });

            return redirect()->route('mypage.reservations.index')->with('status', '予約が完了しました。');
        } catch (\Exception $e) {
            return back()->with('error', '予約に失敗しました。別の時間帯をお試しください。');
        }
    }
}

