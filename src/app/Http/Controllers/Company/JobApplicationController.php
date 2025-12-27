<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        // この会社の求人に対する応募を全て取得
        $applications = JobApplication::whereHas('jobPost', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
        ->where('delete_flg', 0)
        ->with(['jobPost', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('company.applications.index', compact('company', 'applications'));
    }

    public function show(JobApplication $application)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $application->jobPost->company_id !== $company->id) {
            abort(403);
        }

        return view('company.applications.show', compact('company', 'application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $application->jobPost->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'integer'],
            'interview_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $application->status = $validated['status'];
        $application->interview_date = $validated['interview_date'] ?? null;
        $application->save();

        return redirect()->route('company.applications.show', $application)->with('status', 'ステータスを更新しました。');
    }

    public function markAsViewed(Request $request, JobApplication $application)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $application->jobPost->company_id !== $company->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $application->viewed_at = now();
        $application->save();

        return response()->json(['success' => true]);
    }

    public function markMultipleAsViewed(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'application_ids' => ['required', 'array'],
            'application_ids.*' => ['integer', 'exists:job_applications,id'],
        ]);

        $applications = JobApplication::whereIn('id', $validated['application_ids'])
            ->whereHas('jobPost', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->get();

        foreach ($applications as $application) {
            $application->viewed_at = now();
            $application->save();
        }

        return response()->json(['success' => true, 'count' => $applications->count()]);
    }
}

