<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('delete_flg', 0)
            ->orderBy('price_monthly')
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_monthly' => ['required', 'integer', 'min:0'],
            'features_bitmask' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        Plan::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price_monthly' => $validated['price_monthly'],
            'features_bitmask' => $validated['features_bitmask'] ?? 0,
            'status' => $validated['status'],
            'delete_flg' => 0,
        ]);

        return redirect()->route('admin.plans.index')->with('status', 'プランを作成しました。');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_monthly' => ['required', 'integer', 'min:0'],
            'features_bitmask' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $plan->name = $validated['name'];
        $plan->description = $validated['description'] ?? null;
        $plan->price_monthly = $validated['price_monthly'];
        $plan->features_bitmask = $validated['features_bitmask'] ?? 0;
        $plan->status = $validated['status'];
        $plan->save();

        return redirect()->route('admin.plans.index')->with('status', 'プラン情報を更新しました。');
    }

    public function destroy(Plan $plan)
    {
        // 既存の契約がある場合は削除できないようにする
        $activeSubscriptions = $plan->subscriptions()
            ->whereIn('status', [\App\Models\Subscription::STATUS_ACTIVE, \App\Models\Subscription::STATUS_TRIAL])
            ->where('delete_flg', 0)
            ->count();

        if ($activeSubscriptions > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'このプランには有効な契約が存在するため削除できません。');
        }

        $plan->delete_flg = 1;
        $plan->save();

        return redirect()->route('admin.plans.index')->with('status', 'プランを削除しました。');
    }
}

