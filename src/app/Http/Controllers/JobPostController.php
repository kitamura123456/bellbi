<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostController extends Controller
{
    /**
     * 公開中の求人一覧
     */
    public function index(Request $request)
    {
        $query = JobPost::with(['company', 'store', 'tags', 'images'])
            ->where('status', 1) // 公開中
            ->where('delete_flg', 0);

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
            ->withCount(['jobPosts' => function($query) {
                $query->where('status', 1)
                    ->where('delete_flg', 0);
            }])
            ->orderBy('name')
            ->get();

        // 各都道府県の件数を取得
        $areaCounts = JobPost::where('status', 1)
            ->where('delete_flg', 0)
            ->whereNotNull('prefecture_code')
            ->selectRaw('prefecture_code, count(*) as count')
            ->groupBy('prefecture_code')
            ->pluck('count', 'prefecture_code')
            ->toArray();

        // 各雇用形態の件数を取得
        $employmentTypeCounts = JobPost::where('status', 1)
            ->where('delete_flg', 0)
            ->whereNotNull('employment_type')
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

        // 画像を読み込む
        $job->load('images');

        // ログインユーザーが既に応募済みかチェック
        $hasApplied = false;
        if (Auth::check()) {
            $hasApplied = JobApplication::where('job_post_id', $job->id)
                ->where('user_id', Auth::id())
                ->where('delete_flg', 0)
                ->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied'));
    }
}


