<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\ScoutProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoutProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->scoutProfile ?? new ScoutProfile(['user_id' => $user->id, 'is_public' => 0]);

        return view('mypage.scout-profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'industry_type' => ['required', 'integer'],
            'desired_job_category' => ['required', 'integer'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'desired_work_style' => ['nullable', 'integer'],
            'is_public' => ['required', 'integer', 'in:0,1'],
        ]);

        $profile = $user->scoutProfile;
        
        if ($profile) {
            $profile->update($validated);
        } else {
            $user->scoutProfile()->create(array_merge($validated, ['delete_flg' => 0]));
        }

        return redirect()->route('mypage.scout-profile.edit')->with('status', 'スカウト用プロフィールを更新しました。');
    }
}


