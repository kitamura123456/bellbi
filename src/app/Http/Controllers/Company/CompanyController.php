<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        $company->update($validated);

        return redirect()->route('company.info')->with('status', '会社情報を更新しました。');
    }
}

