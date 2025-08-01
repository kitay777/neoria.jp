<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function show(Application $application)
    {
        // 応募された仕事のオーナーだけが見られるように制限
        abort_unless($application->work->user_id === Auth::id(), 403);

        return view('applications.show', compact('application'));
    }
    public function myApplications()
    {
        $applications = Application::with([
            'work.user', // ← 発注者の情報を取得
            'work.category',
            'messages' => fn($q) => $q->latest()->limit(1),
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->paginate(10);

        return view('applications.my_applications', compact('applications'));
    }

}

