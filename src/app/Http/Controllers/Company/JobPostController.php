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

        // ギャラリー画像の処理
        if ($request->hasFile('gallery_images')) {
            $sortOrder = $request->input('gallery_sort_order', []);
            $maxSortOrder = JobPostImage::where('job_post_id', $jobPost->id)->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('job_posts', 'public');
                $order = isset($sortOrder[$index]) ? (int)$sortOrder[$index] : ($maxSortOrder + $index + 1);
                
                JobPostImage::create([
                    'job_post_id' => $jobPost->id,
                    'path' => $path,
                    'sort_order' => $order,
                    'delete_flg' => 0,
                ]);
            }
        }

        // 並び替えの処理（既存画像の順序更新）
        if ($request->filled('gallery_sort_order')) {
            $sortOrders = $request->input('gallery_sort_order');
            foreach ($sortOrders as $imageId => $order) {
                JobPostImage::where('id', $imageId)
                    ->where('job_post_id', $jobPost->id)
                    ->update(['sort_order' => (int)$order]);
            }
        }

        // 削除フラグの処理
        if ($request->filled('delete_gallery_images')) {
            $deleteIds = $request->input('delete_gallery_images');
            foreach ($deleteIds as $imageId) {
                $image = JobPostImage::find($imageId);
                if ($image && $image->job_post_id === $jobPost->id) {
                    // ストレージから削除
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete_flg = 1;
                    $image->save();
                }
            }
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
        $jobPost->load('images');

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

        $jobPost->update($data);

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

        // ギャラリー画像の処理
        if ($request->hasFile('gallery_images')) {
            $sortOrder = $request->input('gallery_sort_order', []);
            $maxSortOrder = JobPostImage::where('job_post_id', $jobPost->id)->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('job_posts', 'public');
                $order = isset($sortOrder[$index]) ? (int)$sortOrder[$index] : ($maxSortOrder + $index + 1);
                
                JobPostImage::create([
                    'job_post_id' => $jobPost->id,
                    'path' => $path,
                    'sort_order' => $order,
                    'delete_flg' => 0,
                ]);
            }
        }

        // 並び替えの処理（既存画像の順序更新）
        if ($request->filled('gallery_sort_order')) {
            $sortOrders = $request->input('gallery_sort_order');
            foreach ($sortOrders as $imageId => $order) {
                JobPostImage::where('id', $imageId)
                    ->where('job_post_id', $jobPost->id)
                    ->update(['sort_order' => (int)$order]);
            }
        }

        // 削除フラグの処理
        if ($request->filled('delete_gallery_images')) {
            $deleteIds = $request->input('delete_gallery_images');
            foreach ($deleteIds as $imageId) {
                $image = JobPostImage::find($imageId);
                if ($image && $image->job_post_id === $jobPost->id) {
                    // ストレージから削除
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete_flg = 1;
                    $image->save();
                }
            }
        }

        // 最初の画像をサムネイルとして設定
        $firstImage = JobPostImage::where('job_post_id', $jobPost->id)
            ->where('delete_flg', 0)
            ->orderBy('sort_order')
            ->first();
        
        if ($firstImage) {
            $jobPost->thumbnail_image = $firstImage->path;
            $jobPost->save();
        } else {
            // 画像がなくなった場合はサムネイルもクリア
            $jobPost->thumbnail_image = null;
            $jobPost->save();
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

