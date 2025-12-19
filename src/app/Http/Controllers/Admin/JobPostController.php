<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Company;
use App\Models\Store;
use App\Enums\Todofuken;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class JobPostController extends Controller
{
    public function index()
    {
        $jobPosts = JobPost::with(['company', 'store'])
            ->where('delete_flg', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.job-posts.index', compact('jobPosts'));
    }

    public function edit(JobPost $jobPost)
    {
        $companies = Company::where('delete_flg', 0)
            ->orderBy('name')
            ->get();
        
        $stores = Store::where('delete_flg', 0)
            ->orderBy('name')
            ->get();
        
        $prefectures = Todofuken::cases();

        return view('admin.job-posts.edit', compact('jobPost', 'companies', 'stores', 'prefectures'));
    }

    public function update(Request $request, JobPost $jobPost)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'store_id' => ['nullable', 'integer', 'exists:stores,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'job_category' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5, 99])],
            'employment_type' => ['required', 'integer', Rule::in([1, 2, 3, 4])],
            'prefecture_code' => ['nullable', 'integer', 'min:1', 'max:47'],
            'city' => ['nullable', 'string', 'max:100'],
            'min_salary' => ['nullable', 'integer', 'min:0'],
            'max_salary' => ['nullable', 'integer', 'min:0'],
            'work_location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'integer', Rule::in([0, 1, 2])],
            'publish_start_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'publish_end_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
        ]);

        // 公開終了日が公開開始日より前になっていないかチェック
        if (!empty($validated['publish_start_at']) && !empty($validated['publish_end_at'])) {
            $startAt = Carbon::createFromFormat('Y-m-d\TH:i', $validated['publish_start_at']);
            $endAt = Carbon::createFromFormat('Y-m-d\TH:i', $validated['publish_end_at']);
            if ($endAt->lt($startAt)) {
                return back()
                    ->withErrors(['publish_end_at' => '公開終了日は公開開始日より後の日時を指定してください。'])
                    ->withInput();
            }
        }

        $jobPost->company_id = $validated['company_id'];
        $jobPost->store_id = $validated['store_id'] ?? null;
        $jobPost->title = $validated['title'];
        $jobPost->description = $validated['description'];
        $jobPost->job_category = $validated['job_category'];
        $jobPost->employment_type = $validated['employment_type'];
        $jobPost->prefecture_code = $validated['prefecture_code'] ?? null;
        $jobPost->city = $validated['city'] ?? null;
        $jobPost->min_salary = $validated['min_salary'] ?? null;
        $jobPost->max_salary = $validated['max_salary'] ?? null;
        $jobPost->work_location = $validated['work_location'] ?? null;
        $jobPost->status = $validated['status'];
        $jobPost->publish_start_at = !empty($validated['publish_start_at'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['publish_start_at'])
            : null;
        $jobPost->publish_end_at = !empty($validated['publish_end_at'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['publish_end_at'])
            : null;
        $jobPost->save();

        return redirect()->route('admin.job-posts.index')->with('status', '求人情報を更新しました。');
    }

    public function destroy(JobPost $jobPost)
    {
        $jobPost->delete_flg = 1;
        $jobPost->save();

        return redirect()->route('admin.job-posts.index')->with('status', '求人を削除しました。');
    }
}

