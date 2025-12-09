<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\ScoutProfile;
use App\Models\ScoutMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoutController extends Controller
{
    // スカウト候補者検索
    public function search(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $query = ScoutProfile::where('is_public', 1)
            ->where('delete_flg', 0)
            ->with('user');

        // 業種で絞り込み
        if ($request->filled('industry_type')) {
            $query->where('industry_type', $request->industry_type);
        }

        // 職種で絞り込み
        if ($request->filled('desired_job_category')) {
            $query->where('desired_job_category', $request->desired_job_category);
        }

        $profiles = $query->paginate(20);

        return view('company.scouts.search', compact('company', 'profiles'));
    }

    // スカウト送信フォーム
    public function create(ScoutProfile $profile)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.scouts.create', compact('company', 'profile', 'stores'));
    }

    // スカウト送信処理
    public function store(Request $request, ScoutProfile $profile)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'from_store_id' => ['nullable', 'exists:stores,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        ScoutMessage::create([
            'from_company_id' => $company->id,
            'from_store_id' => $validated['from_store_id'] ?? null,
            'to_user_id' => $profile->user_id,
            'scout_profile_id' => $profile->id,
            'status' => ScoutMessage::STATUS_SENT,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'delete_flg' => 0,
        ]);

        return redirect()->route('company.scouts.sent')->with('status', 'スカウトを送信しました。');
    }

    // 送信済みスカウト一覧
    public function sent()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $scouts = ScoutMessage::where('from_company_id', $company->id)
            ->where('delete_flg', 0)
            ->with(['toUser', 'fromStore'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('company.scouts.sent', compact('company', 'scouts'));
    }

    // スカウト詳細
    public function show(ScoutMessage $scout)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $scout->from_company_id !== $company->id) {
            abort(403);
        }

        return view('company.scouts.show', compact('company', 'scout'));
    }
}


