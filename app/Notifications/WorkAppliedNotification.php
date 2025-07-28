<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Work;
use App\Models\User;

class WorkAppliedNotification extends Notification
{
    use Queueable;

    protected $work;
    protected $applicant;

    public function __construct(Work $work, User $applicant)
    {
        $this->work = $work;
        $this->applicant = $applicant;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('新しい申し込みが届きました')
            ->greeting("{$this->applicant->name} さんから申し込みがあります。")
            ->line("仕事タイトル: {$this->work->title}")
            ->action('詳細を確認', route('works.show', $this->work))
            ->line('今後の対応をお願いします。');
    }
}
