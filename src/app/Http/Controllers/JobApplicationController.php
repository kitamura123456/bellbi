<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * 応募処理
     */
    public function store(Request $request, JobPost $job): RedirectResponse
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        // 既に応募済みかチェック
        $existingApplication = JobApplication::where('job_post_id', $job->id)
            ->where('user_id', $userId)
            ->where('delete_flg', 0)
            ->first();

        if ($existingApplication) {
            return redirect()
                ->route('jobs.show', $job)
                ->with('error', 'この求人には既に応募済みです。');
        }

        JobApplication::create([
            'job_post_id' => $job->id,
            'user_id' => $userId,
            'status' => 1,
            'message' => $request->input('message'),
            'delete_flg' => 0,
        ]);

        return redirect()
            ->route('jobs.show', $job)
            ->with('status', '応募が完了しました。');
    }
}


