<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $messageText;
    public $applicationId;
    public $createdAt;
    public $user;

    public function __construct(Message $message)
    {
        $message->loadMissing('user');

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
        return [
            'message' => $this->messageText,
            'user' => $this->user,
            'created_at' => $this->createdAt,
        ];
    }
}
