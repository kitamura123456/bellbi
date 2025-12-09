<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $user = Auth::user();

        $reservations = Reservation::where('user_id', $user->id)
            ->where('delete_flg', 0)
            ->with(['store.company', 'staff', 'reservationMenus'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('mypage.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $user = Auth::user();

        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        $reservation->load(['store.company', 'staff', 'reservationMenus.serviceMenu']);

        $canCancel = $this->reservationService->canCancel($reservation);

        return view('mypage.reservations.show', compact('reservation', 'canCancel'));
    }

    public function cancel(Reservation $reservation)
    {
        $user = Auth::user();

        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        if (!$this->reservationService->canCancel($reservation)) {
            return back()->with('error', 'キャンセル期限を過ぎています。店舗に直接ご連絡ください。');
        }

        $reservation->status = Reservation::STATUS_USER_CANCELLED;
        $reservation->save();

        return redirect()->route('mypage.reservations.index')->with('status', '予約をキャンセルしました。');
    }
}

