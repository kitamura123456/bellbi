<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $jobPosts = $company->jobPosts()->where('delete_flg', 0)->orderBy('created_at', 'desc')->get();

        return view('company.job-posts.index', compact('company', 'jobPosts'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();
        $prefectures = \App\Services\LocationService::getPrefectures();

        return view('company.job-posts.create', compact('company', 'stores', 'prefectures'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'store_id' => ['nullable', 'exists:stores,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'job_category' => ['required', 'integer'],
            'employment_type' => ['required', 'integer'],
            'prefecture_code' => ['nullable', 'integer', 'min:1', 'max:47'],
            'city' => ['nullable', 'string', 'max:100'],
            'min_salary' => ['nullable', 'integer', 'min:0'],
            'max_salary' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'integer'],

            'thumbnail_image' => $request->input('template_image') ? 'nullable' : 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'template_image' => ['nullable', 'string'],
        ]);

        $data=[
            'store_id' => $validated['store_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'job_category' => $validated['job_category'],
            'employment_type' => $validated['employment_type'],
            'prefecture_code' => $validated['prefecture_code'] ?? null,
            'city' => $validated['city'] ?? null,
            'min_salary' => $validated['min_salary'],
            'max_salary' => $validated['max_salary'],
            'status' => $validated['status'],
            'delete_flg' => 0,
        ];

        
        // 画像アップロード処理
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')->store('stores', 'public');
            $data['thumbnail_image'] = $path;
        } elseif ($request->input('template_image')) {
            // テンプレート画像を選択した場合
            $data['thumbnail_image'] = $request->input('template_image');
        }

        $company->jobPosts()->create($data);


        return redirect()->route('company.job-posts.index')->with('status', '求人を作成しました。');
    }

    public function edit(JobPost $jobPost)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $jobPost->company_id !== $company->id) {
            abort(403);
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();
        $prefectures = \App\Services\LocationService::getPrefectures();

        return view('company.job-posts.edit', compact('company', 'stores', 'jobPost', 'prefectures'));
    }

    public function update(Request $request, JobPost $jobPost)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $jobPost->company_id !== $company->id) {
            abort(403);
        }

        $data = $request->validate([
            'store_id' => ['nullable', 'exists:stores,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'job_category' => ['required', 'integer'],
            'employment_type' => ['required', 'integer'],
            'prefecture_code' => ['nullable', 'integer', 'min:1', 'max:47'],
            'city' => ['nullable', 'string', 'max:100'],
            'min_salary' => ['nullable', 'integer', 'min:0'],
            'max_salary' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'integer'],
        ]);

        
        // 画像アップロード処理
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')->store('stores', 'public');
            $data['thumbnail_image'] = $path;
        } elseif ($request->input('template_image')) {
            // テンプレート画像を選択した場合
            $data['thumbnail_image'] = $request->input('template_image');
        }


        $jobPost->update($data);

        return redirect()->route('company.job-posts.index')->with('status', '求人を更新しました。');
    }

    public function destroy(JobPost $jobPost)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $jobPost->company_id !== $company->id) {
            abort(403);
        }

        $jobPost->delete_flg = 1;
        $jobPost->save();

        return redirect()->route('company.job-posts.index')->with('status', '求人を削除しました。');
    }
}

