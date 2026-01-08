<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Tag;
use App\Models\City;
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
        // 入力検証（セキュリティ強化）
        // GETリクエストのため、バリデーションエラーは無視して有効な値のみを使用
        $validated = [];
        
        // キーワード検証
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            if (is_string($keyword) && mb_strlen($keyword) <= 255) {
                $validated['keyword'] = trim($keyword);
            }
        }
        
        // エリア検証（整数配列、1-47の範囲）
        if ($request->filled('area')) {
            $areas = (array)$request->input('area');
            $validAreas = [];
            foreach ($areas as $area) {
                $areaInt = filter_var($area, FILTER_VALIDATE_INT);
                if ($areaInt !== false && $areaInt >= 1 && $areaInt <= 47) {
                    $validAreas[] = $areaInt;
                }
            }
            if (!empty($validAreas)) {
                $validated['area'] = array_unique($validAreas);
            }
        }
        
        // 市区町村検証（整数配列、1以上）
        if ($request->filled('city')) {
            $cities = (array)$request->input('city');
            $validCities = [];
            foreach ($cities as $city) {
                $cityInt = filter_var($city, FILTER_VALIDATE_INT);
                if ($cityInt !== false && $cityInt >= 1) {
                    $validCities[] = $cityInt;
                }
            }
            if (!empty($validCities)) {
                $validated['city'] = array_unique($validCities);
            }
        }
        
        // 雇用形態検証（整数配列、1-4の値のみ）
        if ($request->filled('employment_type')) {
            $types = (array)$request->input('employment_type');
            $validTypes = [];
            foreach ($types as $type) {
                $typeInt = filter_var($type, FILTER_VALIDATE_INT);
                if ($typeInt !== false && in_array($typeInt, [1, 2, 3, 4], true)) {
                    $validTypes[] = $typeInt;
                }
            }
            if (!empty($validTypes)) {
                $validated['employment_type'] = array_unique($validTypes);
            }
        }
        
        // タグ検証（整数配列、1以上）
        if ($request->filled('tags')) {
            $tags = (array)$request->input('tags');
            $validTags = [];
            foreach ($tags as $tag) {
                $tagInt = filter_var($tag, FILTER_VALIDATE_INT);
                if ($tagInt !== false && $tagInt >= 1) {
                    $validTags[] = $tagInt;
                }
            }
            if (!empty($validTags)) {
                $validated['tags'] = array_unique($validTags);
            }
        }

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

        // キーワード検索（検証済みの値を安全に使用）
        if (!empty($validated['keyword'])) {
            $keyword = trim($validated['keyword']);
            if (!empty($keyword)) {
                // LIKEクエリはEloquentのパラメータバインディングで安全に処理される
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
        }

        // エリア検索（検証済みの整数配列を使用）
        if (!empty($validated['area'])) {
            $areas = array_map('intval', $validated['area']);
            $areas = array_filter($areas, function($v) {
                return $v >= 1 && $v <= 47;
            });
            if (!empty($areas)) {
                $query->whereIn('prefecture_code', $areas);
            }
        }

        // 市区町村検索（検証済みの整数配列を使用）
        if (!empty($validated['city'])) {
            $cityCodes = array_map('intval', $validated['city']);
            $cityCodes = array_filter($cityCodes, function($v) {
                return $v >= 1;
            });
            if (!empty($cityCodes)) {
                $query->whereIn('city_code', $cityCodes);
            }
        }

        // 雇用形態検索（検証済みの整数配列を使用）
        if (!empty($validated['employment_type'])) {
            $types = array_map('intval', $validated['employment_type']);
            $types = array_filter($types, function($v) {
                return in_array($v, [1, 2, 3, 4]);
            });
            if (!empty($types)) {
                $query->whereIn('employment_type', $types);
            }
        }

        // タグ検索（検証済みの整数配列を使用）
        if (!empty($validated['tags'])) {
            $tagIds = array_map('intval', $validated['tags']);
            $tagIds = array_filter($tagIds, function($v) {
                return $v >= 1;
            });
            if (!empty($tagIds)) {
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

        // 選択された都道府県の市区町村を取得（検証済みのデータを使用）
        $selectedPrefectures = [];
        if (!empty($validated['area'])) {
            $selectedPrefectures = array_map('intval', $validated['area']);
            $selectedPrefectures = array_filter($selectedPrefectures, function($v) {
                return $v >= 1 && $v <= 47;
            });
        }
        
        $cities = collect();
        if (!empty($selectedPrefectures)) {
            $cities = City::whereIn('prefecture_code', $selectedPrefectures)
                ->orderBy('prefecture_code')
                ->orderBy('name')
                ->get();
        }

        // 各市区町村の件数を取得
        $cityCounts = [];
        if (!empty($selectedPrefectures)) {
            $cityCounts = JobPost::where('status', 1)
                ->where('delete_flg', 0)
                ->whereIn('prefecture_code', $selectedPrefectures)
                ->whereNotNull('city_code')
                ->where(function($q) use ($now) {
                    $q->whereNull('publish_start_at')
                      ->orWhere('publish_start_at', '<=', $now);
                })
                ->where(function($q) use ($now) {
                    $q->whereNull('publish_end_at')
                      ->orWhere('publish_end_at', '>', $now);
                })
                ->selectRaw('city_code, count(*) as count')
                ->groupBy('city_code')
                ->pluck('count', 'city_code')
                ->toArray();
        }

        return view('jobs.index', compact('jobs', 'tags', 'areaCounts', 'employmentTypeCounts', 'userApplications', 'cities', 'cityCounts', 'selectedPrefectures'));
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


