<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JobPostController extends Controller
{
    /**
     * 公開中の求人一覧
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        
        $query = JobPost::with(['company', 'store', 'tags'])
            ->where('status', 1) // 公開中
            ->where('delete_flg', 0)
            // 公開開始日のチェック：開始日が設定されている場合は現在日時以降であること
            ->where(function($q) use ($now) {
                $q->whereNull('publish_start_at')
                  ->orWhere('publish_start_at', '<=', $now);
            })
            // 公開終了日のチェック：終了日が設定されている場合は現在日時より後であること
            ->where(function($q) use ($now) {
                $q->whereNull('publish_end_at')
                  ->orWhere('publish_end_at', '>', $now);
            });

        if($request->filled('keyword') && !empty(array_filter((array)$request->input('keyword')))){
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword){
                $q->where('title','like',"%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->orWhereHas('company', function($q2) use ($keyword){
                    $q2->where('name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('store', function($q2) use ($keyword){
                    $q2->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if($request->filled('area') && !empty(array_filter((array)$request->input('area')))){
            $areas = (array)$request->input('area');
            $query->whereIn('prefecture_code', $areas);
        }

        if($request->filled('employment_type') && !empty(array_filter((array)$request->input('employment_type')))){
            $types = (array)$request->input('employment_type');
            $query->whereIn('employment_type', $types);
        }

        if($request->filled('tags')){
            $tagIds = array_filter((array)$request->input('tags'));
            if(!empty($tagIds)){
                $query->whereHas('tags', function($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            }
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();
        
        // ログインユーザーの応募情報を取得（不採用の応募を識別するため）
        $userApplications = collect();
        if (Auth::check()) {
            $userApplications = JobApplication::where('user_id', Auth::id())
                ->where('delete_flg', 0)
                ->whereIn('job_post_id', $jobs->pluck('id'))
                ->pluck('status', 'job_post_id');
        }
        
        // 各タグの件数を取得（公開中の求人のみ）
        $tags = Tag::where('delete_flg', 0)
            ->withCount(['jobPosts' => function($query) use ($now) {
                $query->where('status', 1)
                    ->where('delete_flg', 0)
                    ->where(function($q) use ($now) {
                        $q->whereNull('publish_start_at')
                          ->orWhere('publish_start_at', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('publish_end_at')
                          ->orWhere('publish_end_at', '>', $now);
                    });
            }])
            ->orderBy('name')
            ->get();

        // 各都道府県の件数を取得
        $areaCounts = JobPost::where('status', 1)
            ->where('delete_flg', 0)
            ->whereNotNull('prefecture_code')
            ->where(function($q) use ($now) {
                $q->whereNull('publish_start_at')
                  ->orWhere('publish_start_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('publish_end_at')
                  ->orWhere('publish_end_at', '>', $now);
            })
            ->selectRaw('prefecture_code, count(*) as count')
            ->groupBy('prefecture_code')
            ->pluck('count', 'prefecture_code')
            ->toArray();

        // 各雇用形態の件数を取得
        $employmentTypeCounts = JobPost::where('status', 1)
            ->where('delete_flg', 0)
            ->whereNotNull('employment_type')
            ->where(function($q) use ($now) {
                $q->whereNull('publish_start_at')
                  ->orWhere('publish_start_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('publish_end_at')
                  ->orWhere('publish_end_at', '>', $now);
            })
            ->selectRaw('employment_type, count(*) as count')
            ->groupBy('employment_type')
            ->pluck('count', 'employment_type')
            ->toArray();

        return view('jobs.index', compact('jobs', 'tags', 'areaCounts', 'employmentTypeCounts', 'userApplications'));
    }

    /**
     * 求人詳細
     */
    public function show(JobPost $job)
    {
        if ($job->status !== 1 || $job->delete_flg !== 0) {
            abort(404);
        }

        $now = Carbon::now();
        
        // 公開開始日のチェック
        if ($job->publish_start_at && $job->publish_start_at->gt($now)) {
            abort(404); // まだ公開開始前
        }
        
        // 公開終了後でもページは表示可能にする（404にしない）
        $isExpired = false;
        if ($job->publish_end_at && $job->publish_end_at->lte($now)) {
            $isExpired = true; // 公開期間終了フラグ
        }

        // ログインユーザーが既に応募済みかチェック
        $hasApplied = false;
        if (Auth::check()) {
            $hasApplied = JobApplication::where('job_post_id', $job->id)
                ->where('user_id', Auth::id())
                ->where('delete_flg', 0)
                ->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied', 'isExpired'));
    }
}


