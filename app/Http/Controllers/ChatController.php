<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function show(Application $application)
    {
        // アクセス制限（応募者 or 投稿者のみ）
        abort_unless(
            Auth::id() === $application->user_id || Auth::id() === $application->work->user_id,
            403
        );

        $messages = $application->messages()->with('user')->latest()->get();

        return view('chat.show', compact('application', 'messages'));
    }

    public function send(Request $request, Application $application)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // アクセス制限
        abort_unless(
            Auth::id() === $application->user_id || Auth::id() === $application->work->user_id,
            403
        );

        $application->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('chat.with', $application);
    }
}