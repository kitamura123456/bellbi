<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\ServiceMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->with(['serviceMenus' => function($query) {
            $query->where('delete_flg', 0)->orderBy('display_order');
        }])->get();

        return view('company.menus.index', compact('company', 'stores'));
    }

    public function create()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.menus.create', compact('company', 'stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'thumbnail_image' => ['nullable', 'image', 'max:2048'],
            'template_image' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:30'],
            'price' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'integer'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ]);

        $store = Store::where('id', $validated['store_id'])
            ->where('company_id', $company->id)
            ->firstOrFail();

        // 30分単位に丸める
        $validated['duration_minutes'] = (int)(ceil($validated['duration_minutes'] / 30) * 30);

        $data = array_merge($validated, ['delete_flg' => 0]);

        // 画像アップロード処理
        if ($request->hasFile('thumbnail_image')) {
            $path = $request->file('thumbnail_image')->store('menus', 'public');
            $data['thumbnail_image'] = $path;
        } elseif ($request->input('template_image')) {
            // テンプレート画像を選択した場合
            $data['thumbnail_image'] = $request->input('template_image');
        }

        ServiceMenu::create($data);

        return redirect()->route('company.menus.index')->with('status', 'メニューを登録しました。');
    }

    public function edit(ServiceMenu $menu)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $menu->store->company_id !== $company->id) {
            abort(403);
        }

        $stores = $company->stores()->where('delete_flg', 0)->get();

        return view('company.menus.edit', compact('company', 'menu', 'stores'));
    }

    public function update(Request $request, ServiceMenu $menu)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $menu->store->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'thumbnail_image' => ['nullable', 'image', 'max:2048'],
            'template_image' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:30'],
            'price' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'integer'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ]);

        $store = Store::where('id', $validated['store_id'])
            ->where('company_id', $company->id)
            ->firstOrFail();

        // 30分単位に丸める
        $validated['duration_minutes'] = (int)(ceil($validated['duration_minutes'] / 30) * 30);

        $data = $validated;

        // 画像アップロード処理
        if ($request->hasFile('thumbnail_image')) {
            // 古い画像を削除（アップロードされた画像のみ）
            if ($menu->thumbnail_image && strpos($menu->thumbnail_image, 'templates/') === false && Storage::disk('public')->exists($menu->thumbnail_image)) {
                Storage::disk('public')->delete($menu->thumbnail_image);
            }
            $path = $request->file('thumbnail_image')->store('menus', 'public');
            $data['thumbnail_image'] = $path;
        } elseif ($request->input('template_image')) {
            // テンプレート画像を選択した場合
            if ($menu->thumbnail_image && strpos($menu->thumbnail_image, 'templates/') === false && Storage::disk('public')->exists($menu->thumbnail_image)) {
                Storage::disk('public')->delete($menu->thumbnail_image);
            }
            $data['thumbnail_image'] = $request->input('template_image');
        }

        $menu->update($data);

        return redirect()->route('company.menus.index')->with('status', 'メニュー情報を更新しました。');
    }

    public function destroy(ServiceMenu $menu)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $menu->store->company_id !== $company->id) {
            abort(403);
        }

        $menu->delete_flg = 1;
        $menu->save();

        return redirect()->route('company.menus.index')->with('status', 'メニューを削除しました。');
    }
}

