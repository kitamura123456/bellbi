<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobPostImage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Todofuken;
use Carbon\Carbon;

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
        $prefectures = Todofuken::cases();
        $tags = Tag::where('delete_flg', 0)->orderBy('name')->get();

        return view('company.job-posts.create', compact('company', 'stores', 'prefectures', 'tags'));
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
            'publish_start_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'publish_end_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'integer', 'exists:tags,id'],
            'new_tags' => ['nullable', 'string'],

            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'gallery_sort_order' => ['nullable', 'array'],
            'gallery_sort_order.*' => ['integer'],
            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer'],
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

        $data = [
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
            'publish_start_at' => !empty($validated['publish_start_at'])
                ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['publish_start_at'])
                : null,
            'publish_end_at' => !empty($validated['publish_end_at'])
                ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['publish_end_at'])
                : null,
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

        $jobPost = $company->jobPosts()->create($data);

        // タグの処理
        $tagIds = [];
        if ($request->filled('tags')) {
            $tagIds = array_filter($request->input('tags'));
        }
        
        // 新しいタグを追加
        if ($request->filled('new_tags')) {
            $newTagNames = array_map('trim', explode(',', $request->input('new_tags')));
            foreach ($newTagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['delete_flg' => 0]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }

        if (!empty($tagIds)) {
            $jobPost->tags()->sync(array_unique($tagIds));
        } else {
            $jobPost->tags()->detach();
        }

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
        $prefectures = Todofuken::cases();
        $tags = Tag::where('delete_flg', 0)->orderBy('name')->get();

        return view('company.job-posts.edit', compact('company', 'stores', 'jobPost', 'prefectures', 'tags'));
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
            'publish_start_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'publish_end_at' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'integer', 'exists:tags,id'],
            'new_tags' => ['nullable', 'string'],
        ]);

        // 公開終了日が公開開始日より前になっていないかチェック
        if (!empty($data['publish_start_at']) && !empty($data['publish_end_at'])) {
            $startAt = Carbon::createFromFormat('Y-m-d\TH:i', $data['publish_start_at']);
            $endAt = Carbon::createFromFormat('Y-m-d\TH:i', $data['publish_end_at']);
            if ($endAt->lt($startAt)) {
                return back()
                    ->withErrors(['publish_end_at' => '公開終了日は公開開始日より後の日時を指定してください。'])
                    ->withInput();
            }
        }

        // 画像アップロード処理
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')->store('stores', 'public');
            $data['thumbnail_image'] = $path;
        } elseif ($request->input('template_image')) {
            // テンプレート画像を選択した場合
            $data['thumbnail_image'] = $request->input('template_image');
        }

        $updateData = $data;
        $updateData['publish_start_at'] = !empty($data['publish_start_at'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $data['publish_start_at'])
            : null;
        $updateData['publish_end_at'] = !empty($data['publish_end_at'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $data['publish_end_at'])
            : null;

        $jobPost->update($updateData);

        // タグの処理
        $tagIds = [];
        if ($request->filled('tags')) {
            $tagIds = array_filter($request->input('tags'));
        }
        
        // 新しいタグを追加
        if ($request->filled('new_tags')) {
            $newTagNames = array_map('trim', explode(',', $request->input('new_tags')));
            foreach ($newTagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['delete_flg' => 0]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }

        if (!empty($tagIds)) {
            $jobPost->tags()->sync(array_unique($tagIds));
        } else {
            $jobPost->tags()->detach();
        }

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

