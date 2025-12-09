<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Store;
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

        $company->stores()->create($data);

        return redirect()->route('company.stores.index')->with('status', '店舗を追加しました。');
    }

    public function edit(Store $store)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $store->company_id !== $company->id) {
            abort(403);
        }

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

