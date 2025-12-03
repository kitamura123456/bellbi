<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    /**
     * 公開中の求人一覧
     */
    public function index()
    {
        $jobs = JobPost::with(['company', 'store'])
            ->where('status', 1) // 公開中
            ->where('delete_flg', 0)
            ->latest()
            ->paginate(10);

        return view('jobs.index', compact('jobs'));
    }

    /**
     * 求人詳細
     */
    public function show(JobPost $job)
    {
        if ($job->status !== 1 || $job->delete_flg !== 0) {
            abort(404);
        }

        return view('jobs.show', compact('job'));
    }
}


