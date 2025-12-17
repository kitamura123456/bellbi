<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        return view('company.info.show', compact('company'));
    }

    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $industryTypes = \App\Services\BusinessCategoryService::getIndustryTypes();
        $categories = $company->industry_type 
            ? \App\Services\BusinessCategoryService::getCategoriesByIndustry($company->industry_type)
            : [];

        return view('company.info.edit', compact('company', 'industryTypes', 'categories'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'industry_type' => ['required', 'integer'],
            'business_category' => ['nullable', 'integer'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'tel' => ['nullable', 'string', 'max:50'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'gallery_sort_order' => ['nullable', 'array'],
            'gallery_sort_order.*' => ['integer'],
            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer'],
        ]);

        $company->update($validated);

        // ギャラリー画像の処理
        if ($request->hasFile('gallery_images')) {
            $sortOrder = $request->input('gallery_sort_order', []);
            $maxSortOrder = CompanyImage::where('company_id', $company->id)->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('companies', 'public');
                $order = isset($sortOrder[$index]) ? (int)$sortOrder[$index] : ($maxSortOrder + $index + 1);
                
                CompanyImage::create([
                    'company_id' => $company->id,
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
                CompanyImage::where('id', $imageId)
                    ->where('company_id', $company->id)
                    ->update(['sort_order' => (int)$order]);
            }
        }

        // 削除フラグの処理
        if ($request->filled('delete_gallery_images')) {
            $deleteIds = $request->input('delete_gallery_images');
            foreach ($deleteIds as $imageId) {
                $image = CompanyImage::find($imageId);
                if ($image && $image->company_id === $company->id) {
                    // ストレージから削除
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete_flg = 1;
                    $image->save();
                }
            }
        }

        return redirect()->route('company.info')->with('status', '会社情報を更新しました。');
    }
}

