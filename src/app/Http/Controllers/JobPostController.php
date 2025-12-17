<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Tag;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    /**
     * 公開中の求人一覧
     */
    public function index(Request $request)
    {
        $query = JobPost::with(['company', 'store', 'tags'])
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
        $tags = Tag::where('delete_flg', 0)->orderBy('name')->get();

        return view('jobs.index', compact('jobs', 'tags'));
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


