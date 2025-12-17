<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.stores.index', compact('company', 'stores'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        return view('company.stores.create', compact('company'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'store_type' => ['required', 'integer'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tel' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:2000'],
            'thumbnail_image' => ['nullable', 'image', 'max:2048'],
            'template_image' => ['nullable', 'string'],
            'accepts_reservations' => ['nullable', 'integer', 'in:0,1'],
            'cancel_deadline_hours' => ['nullable', 'integer', 'min:0'],
            'max_concurrent_reservations' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $data = [
            'name' => $validated['name'],
            'store_type' => $validated['store_type'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'tel' => $validated['tel'],
            'description' => $validated['description'] ?? null,
            'accepts_reservations' => $validated['accepts_reservations'] ?? 0,
            'cancel_deadline_hours' => $validated['cancel_deadline_hours'] ?? 24,
            'max_concurrent_reservations' => $validated['max_concurrent_reservations'] ?? 3,
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

        $store = $company->stores()->create($data);

        // ギャラリー画像の処理
        if ($request->hasFile('gallery_images')) {
            $sortOrder = $request->input('gallery_sort_order', []);
            $maxSortOrder = StoreImage::where('store_id', $store->id)->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('stores', 'public');
                $order = isset($sortOrder[$index]) ? (int)$sortOrder[$index] : ($maxSortOrder + $index + 1);
                
                StoreImage::create([
                    'store_id' => $store->id,
                    'path' => $path,
                    'sort_order' => $order,
                    'delete_flg' => 0,
                ]);
            }
        }

        return redirect()->route('company.stores.index')->with('status', '店舗を追加しました。');
    }

    public function edit(Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $store->load('images');

        return view('company.stores.edit', compact('company', 'store'));
    }

    public function update(Request $request, Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'store_type' => ['required', 'integer'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tel' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:2000'],
            'thumbnail_image' => ['nullable', 'image', 'max:2048'],
            'template_image' => ['nullable', 'string'],
            'accepts_reservations' => ['nullable', 'integer', 'in:0,1'],
            'cancel_deadline_hours' => ['nullable', 'integer', 'min:0'],
            'max_concurrent_reservations' => ['nullable', 'integer', 'min:1', 'max:20'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'gallery_sort_order' => ['nullable', 'array'],
            'gallery_sort_order.*' => ['integer'],
            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer'],
        ]);

        $data = [
            'name' => $validated['name'],
            'store_type' => $validated['store_type'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'tel' => $validated['tel'],
            'description' => $validated['description'] ?? null,
            'accepts_reservations' => $validated['accepts_reservations'] ?? $store->accepts_reservations,
            'cancel_deadline_hours' => $validated['cancel_deadline_hours'] ?? $store->cancel_deadline_hours,
            'max_concurrent_reservations' => $validated['max_concurrent_reservations'] ?? $store->max_concurrent_reservations ?? 3,
        ];

        // 画像アップロード処理
        if ($request->hasFile('thumbnail_image')) {
            // 古い画像を削除（アップロードされた画像のみ）
            if ($store->thumbnail_image && strpos($store->thumbnail_image, 'templates/') === false && Storage::disk('public')->exists($store->thumbnail_image)) {
                Storage::disk('public')->delete($store->thumbnail_image);
            }
            $path = $request->file('thumbnail_image')->store('stores', 'public');
            $data['thumbnail_image'] = $path;
        } elseif ($request->input('template_image')) {
            // テンプレート画像を選択した場合
            if ($store->thumbnail_image && strpos($store->thumbnail_image, 'templates/') === false && Storage::disk('public')->exists($store->thumbnail_image)) {
                Storage::disk('public')->delete($store->thumbnail_image);
            }
            $data['thumbnail_image'] = $request->input('template_image');
        }

        $store->update($data);

        // ギャラリー画像の処理
        if ($request->hasFile('gallery_images')) {
            $sortOrder = $request->input('gallery_sort_order', []);
            $maxSortOrder = StoreImage::where('store_id', $store->id)->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('stores', 'public');
                $order = isset($sortOrder[$index]) ? (int)$sortOrder[$index] : ($maxSortOrder + $index + 1);
                
                StoreImage::create([
                    'store_id' => $store->id,
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
                StoreImage::where('id', $imageId)
                    ->where('store_id', $store->id)
                    ->update(['sort_order' => (int)$order]);
            }
        }

        // 削除フラグの処理
        if ($request->filled('delete_gallery_images')) {
            $deleteIds = $request->input('delete_gallery_images');
            foreach ($deleteIds as $imageId) {
                $image = StoreImage::find($imageId);
                if ($image && $image->store_id === $store->id) {
                    // ストレージから削除
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete_flg = 1;
                    $image->save();
                }
            }
        }

        return redirect()->route('company.stores.index')->with('status', '店舗情報を更新しました。');
    }

    public function destroy(Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

        $store->delete_flg = 1;
        $store->save();

        return redirect()->route('company.stores.index')->with('status', '店舗を削除しました。');
    }
}

