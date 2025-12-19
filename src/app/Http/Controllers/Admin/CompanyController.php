<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Plan;
use App\Services\BusinessCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with(['user', 'plan'])
            ->where('delete_flg', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.companies.index', compact('companies'));
    }

    public function edit(Company $company)
    {
        $plans = Plan::where('delete_flg', 0)
            ->orderBy('price_monthly')
            ->get();

        return view('admin.companies.edit', compact('company', 'plans'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'industry_type' => ['required', 'integer', Rule::in([1, 2, 3])],
            'business_category' => ['nullable', 'integer'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:50'],
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'plan_status' => ['required', 'integer', Rule::in([1, 2, 3, 9])],
        ]);

        $company->name = $validated['name'];
        $company->contact_name = $validated['contact_name'] ?? null;
        $company->industry_type = $validated['industry_type'];
        $company->business_category = $validated['business_category'] ?? null;
        $company->postal_code = $validated['postal_code'] ?? null;
        $company->address = $validated['address'] ?? null;
        $company->tel = $validated['tel'] ?? null;
        $company->plan_id = $validated['plan_id'] ?? null;
        $company->plan_status = $validated['plan_status'];
        $company->save();

        return redirect()->route('admin.companies.index')->with('status', '事業者情報を更新しました。');
    }

    public function destroy(Company $company)
    {
        $company->delete_flg = 1;
        $company->save();

        return redirect()->route('admin.companies.index')->with('status', '事業者を削除しました。');
    }
}

