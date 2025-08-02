<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message; // ← これが必要！

    public $messageText;
    public $applicationId;
    public $createdAt;
    public $user;

    public function __construct(Message $message)
    {
        $message->loadMissing('user');
        $this->message = $message;
        $this->messageText = $message->message;
        $this->applicationId = $message->application_id;
        $this->createdAt = $message->created_at?->toDateTimeString();
        $this->user = [
            'id' => $message->user->id ?? null,
            'name' => $message->user->name ?? '不明',
        ];
    }

    public function broadcastOn()
    {
        return new Channel('chat.' . $this->applicationId);
    }

    public function broadcastWith()
    {
        \Log::info('broadcasting', ['message' => $this->message]);
return [
    'user' => [
        'id' => $this->message->user->id,
        'name' => $this->message->user->name,
    ],
    'message' => $this->message->message,
    'created_at' => $this->message->created_at->toDateTimeString(), // ← 修正
];
    }
}
