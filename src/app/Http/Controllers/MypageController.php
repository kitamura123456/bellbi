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

        return view('mypage.index', compact('user', 'applications'));
    }
}

