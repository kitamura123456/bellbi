<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->with(['schedules' => function($query) {
            $query->where('delete_flg', 0)->orderBy('day_of_week');
        }])->get();

        return view('company.schedules.index', compact('company', 'stores'));
    }

    public function edit(Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $schedules = $store->schedules()->where('delete_flg', 0)->orderBy('day_of_week')->get()->keyBy('day_of_week');

        // 曜日が足りない場合は空のデータを生成
        $weekdays = [
            0 => '日曜日',
            1 => '月曜日',
            2 => '火曜日',
            3 => '水曜日',
            4 => '木曜日',
            5 => '金曜日',
            6 => '土曜日',
        ];

        foreach ($weekdays as $day => $name) {
            if (!isset($schedules[$day])) {
                $schedules[$day] = new StoreSchedule([
                    'store_id' => $store->id,
                    'day_of_week' => $day,
                    'is_open' => 1,
                    'open_time' => '10:00',
                    'close_time' => '19:00',
                ]);
            }
        }

        $schedules = $schedules->sortKeys();

        return view('company.schedules.edit', compact('company', 'store', 'schedules', 'weekdays'));
    }

    public function update(Request $request, Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'schedules' => ['required', 'array'],
            'schedules.*.is_open' => ['required', 'integer', 'in:0,1'],
            'schedules.*.open_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.close_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.max_concurrent_reservations' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        DB::transaction(function () use ($store, $validated) {
            foreach ($validated['schedules'] as $day => $data) {
                $schedule = StoreSchedule::where('store_id', $store->id)
                    ->where('day_of_week', $day)
                    ->where('delete_flg', 0)
                    ->first();

                if ($schedule) {
                    $schedule->update([
                        'is_open' => $data['is_open'],
                        'open_time' => $data['is_open'] ? $data['open_time'] : null,
                        'close_time' => $data['is_open'] ? $data['close_time'] : null,
                        'max_concurrent_reservations' => $data['max_concurrent_reservations'],
                    ]);
                } else {
                    StoreSchedule::create([
                        'store_id' => $store->id,
                        'day_of_week' => $day,
                        'is_open' => $data['is_open'],
                        'open_time' => $data['is_open'] ? $data['open_time'] : null,
                        'close_time' => $data['is_open'] ? $data['close_time'] : null,
                        'max_concurrent_reservations' => $data['max_concurrent_reservations'],
                        'delete_flg' => 0,
                    ]);
                }
            }
        });

        return redirect()->route('company.schedules.index')->with('status', '営業スケジュールを更新しました。');
    }
}

