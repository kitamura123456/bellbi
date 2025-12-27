<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $query = Reservation::whereHas('store', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->where('delete_flg', 0)->with(['store', 'user', 'staff', 'reservationMenus']);

        // 日付フィルタ
        if ($request->filled('date')) {
            $query->where('reservation_date', $request->date);
        } else {
            // デフォルトは今日以降
            $query->where('reservation_date', '>=', now()->toDateString());
        }

        // ステータスフィルタ
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 店舗フィルタ
        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        $reservations = $query->orderBy('reservation_date')->orderBy('start_time')->paginate(20);
        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.reservations.index', compact('company', 'reservations', 'stores'));
    }

    public function show(Reservation $reservation)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $reservation->store->company_id !== $company->id) {
            abort(403);
        }

        $reservation->load(['store', 'user', 'staff', 'reservationMenus.serviceMenu']);

        return view('company.reservations.show', compact('company', 'reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $reservation->store->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:1,2,3,9'],
            'store_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $reservation->update($validated);

        return redirect()->route('company.reservations.show', $reservation)->with('status', '予約ステータスを更新しました。');
    }

    public function markAsViewed(Request $request, Reservation $reservation)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $reservation->store->company_id !== $company->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $reservation->viewed_at = now();
        $reservation->save();

        return response()->json(['success' => true]);
    }

    public function markMultipleAsViewed(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'reservation_ids' => ['required', 'array'],
            'reservation_ids.*' => ['integer', 'exists:reservations,id'],
        ]);

        $reservations = Reservation::whereIn('id', $validated['reservation_ids'])
            ->whereHas('store', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->viewed_at = now();
            $reservation->save();
        }

        return response()->json(['success' => true, 'count' => $reservations->count()]);
    }
}

