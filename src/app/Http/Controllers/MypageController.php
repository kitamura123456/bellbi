<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $applications = $user->jobApplications()
            ->where('delete_flg', 0)
            ->with('jobPost.company')
            ->orderBy('created_at', 'desc')
            ->get();

        // 面接日がある応募を取得（サイドバー下に表示するため）
        $upcomingInterviews = $applications
            ->filter(function ($application) {
                return $application->interview_date 
                    && $application->interview_date->toDateString() >= now()->toDateString();
            })
            ->sortBy('interview_date')
            ->take(5);

        return view('mypage.index', compact('user', 'applications', 'upcomingInterviews'));
    }
}

