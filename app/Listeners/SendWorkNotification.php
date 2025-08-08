<?php

namespace App\Listeners;

use App\Events\WorkCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewWorkInYourCategory;
use App\Models\TimeProduct;
use Illuminate\Support\Facades\Log;


class SendWorkNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(WorkCreated $event)
    {
        Log::Info('SendWorkNotification triggered for work ID: ' . $event->work->id);
        $work = $event->work;

        // 同じカテゴリの TimeProduct を出してるユーザーを抽出
        $userIds = TimeProduct::where('category_id', $work->category_id)
            ->distinct()
            ->pluck('user_id');
            Log::info('リスナー実行開始>>');

        foreach ($userIds as $userId) {
            $user = \App\Models\User::find($userId);
            Log::Info('名前：' . $user->name . ' メール送信: ' . $user->email . ' for work ID: ' . $work->id);
                
            if ($user && $user->email) {
                Mail::to($user->email)->queue(new NewWorkInYourCategory($work, $user));
            }
        }
    }
}
