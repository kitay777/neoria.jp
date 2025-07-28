<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WorkAppliedNotification;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PointLog;


class WorkApplicationController extends Controller
{


    public function store(Request $request, Work $work)
    {
        $user = Auth::user();

        $request->validate([
            'message' => ['nullable', 'string', 'max:1024'],
        ]);

        $requiredPoints = 1000;

        if ($user->points < $requiredPoints) {
            return back()->with('error', '申し込みに必要なポイントが不足しています。');
        }

        if (Application::where('work_id', $work->id)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'すでにこの仕事に申し込んでいます。');
        }

        DB::transaction(function () use ($user, $work, $request, $requiredPoints) {
            // ポイント減算
            $user->points -= $requiredPoints;
            $user->save();

            // 応募登録
            $application = Application::create([
                'work_id' => $work->id,
                'user_id' => $user->id,
                'message' => $request->message,
                'status' => 'pending',
            ]);

// PointLog 保存時（例：WorkApplicationController@store）

            $currentPoints = $user->points;


            PointLog::create([
                'user_id' => $user->id,
                'application_id' => $application->id ?? null,
                'amount' => -$requiredPoints,
                'balance' => $currentPoints,
                'type' => 'apply',
                'description' => "Work応募 ID={$application->id}",
            ]);

        });

        return back()->with('success', '申し込みが完了し、ポイントが消費されました。');
    }
    
}
