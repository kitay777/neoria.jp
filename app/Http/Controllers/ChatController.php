<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\MessageSent;


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

        $message = $application->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);
        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'sent']);
    }
}