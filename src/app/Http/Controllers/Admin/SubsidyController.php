<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subsidy;
use App\Enums\Todofuken;
use App\Services\BusinessCategoryService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubsidyController extends Controller
{
    public function index()
    {
        $subsidies = Subsidy::where('delete_flg', 0)
            ->orderBy('application_start_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.subsidies.index', compact('subsidies'));
    }

    public function create()
    {
        $prefectures = Todofuken::cases();
        $industryTypes = BusinessCategoryService::getIndustryTypes();
        
        return view('admin.subsidies.create', compact('prefectures', 'industryTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'integer', 'in:1,2,3,4'],
            'target_region' => ['nullable', 'integer', 'min:0', 'max:47'],
            'applicable_industry_type' => ['nullable', 'integer', 'in:1,2,3'],
            'application_start_at' => ['nullable', 'date'],
            'application_end_at' => ['nullable', 'date', 'after_or_equal:application_start_at'],
            'status' => ['required', 'integer', 'in:1,2,3'],
            'summary' => ['nullable', 'string'],
            'detail_url' => ['nullable', 'url', 'max:500'],
        ]);

        // 申請期間からステータスを自動判定（オプション）
        $now = Carbon::now();
        if ($validated['application_start_at'] && $validated['application_end_at']) {
            $startDate = Carbon::parse($validated['application_start_at']);
            $endDate = Carbon::parse($validated['application_end_at']);
            
            if ($now->lt($startDate)) {
                // 開始日より前
                $validated['status'] = Subsidy::STATUS_NOT_STARTED;
            } elseif ($now->gt($endDate)) {
                // 終了日より後
                $validated['status'] = Subsidy::STATUS_CLOSED;
            } else {
                // 期間内
                $validated['status'] = Subsidy::STATUS_RECRUITING;
            }
        }

        Subsidy::create([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'target_region' => $validated['target_region'] ?? null,
            'applicable_industry_type' => $validated['applicable_industry_type'] ?? null,
            'application_start_at' => $validated['application_start_at'] ? Carbon::parse($validated['application_start_at']) : null,
            'application_end_at' => $validated['application_end_at'] ? Carbon::parse($validated['application_end_at']) : null,
            'status' => $validated['status'],
            'summary' => $validated['summary'] ?? null,
            'detail_url' => $validated['detail_url'] ?? null,
            'delete_flg' => 0,
        ]);

        return redirect()->route('admin.subsidies.index')->with('status', '補助金情報を追加しました。');
    }

    public function edit(Subsidy $subsidy)
    {
        $prefectures = Todofuken::cases();
        $industryTypes = BusinessCategoryService::getIndustryTypes();
        
        return view('admin.subsidies.edit', compact('subsidy', 'prefectures', 'industryTypes'));
    }

    public function update(Request $request, Subsidy $subsidy)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'integer', 'in:1,2,3,4'],
            'target_region' => ['nullable', 'integer', 'min:0', 'max:47'],
            'applicable_industry_type' => ['nullable', 'integer', 'in:1,2,3'],
            'application_start_at' => ['nullable', 'date'],
            'application_end_at' => ['nullable', 'date', 'after_or_equal:application_start_at'],
            'status' => ['required', 'integer', 'in:1,2,3'],
            'summary' => ['nullable', 'string'],
            'detail_url' => ['nullable', 'url', 'max:500'],
        ]);

        // 申請期間からステータスを自動判定（オプション）
        $now = Carbon::now();
        if ($validated['application_start_at'] && $validated['application_end_at']) {
            $startDate = Carbon::parse($validated['application_start_at']);
            $endDate = Carbon::parse($validated['application_end_at']);
            
            if ($now->lt($startDate)) {
                $validated['status'] = Subsidy::STATUS_NOT_STARTED;
            } elseif ($now->gt($endDate)) {
                $validated['status'] = Subsidy::STATUS_CLOSED;
            } else {
                $validated['status'] = Subsidy::STATUS_RECRUITING;
            }
        }

        $subsidy->title = $validated['title'];
        $subsidy->category = $validated['category'] ?? null;
        $subsidy->target_region = $validated['target_region'] ?? null;
        $subsidy->applicable_industry_type = $validated['applicable_industry_type'] ?? null;
        $subsidy->application_start_at = $validated['application_start_at'] ? Carbon::parse($validated['application_start_at']) : null;
        $subsidy->application_end_at = $validated['application_end_at'] ? Carbon::parse($validated['application_end_at']) : null;
        $subsidy->status = $validated['status'];
        $subsidy->summary = $validated['summary'] ?? null;
        $subsidy->detail_url = $validated['detail_url'] ?? null;
        $subsidy->save();

        return redirect()->route('admin.subsidies.index')->with('status', '補助金情報を更新しました。');
    }

    public function destroy(Subsidy $subsidy)
    {
        $subsidy->delete_flg = 1;
        $subsidy->save();

        return redirect()->route('admin.subsidies.index')->with('status', '補助金情報を削除しました。');
    }
}
