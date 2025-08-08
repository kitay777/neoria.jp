<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
// app/Mail/NewWorkInYourCategory.php
use App\Models\User;
use Illuminate\Support\Facades\Log;


use App\Models\Work;

class NewWorkInYourCategory extends Mailable implements ShouldQueue
{
    public $work;
    public $user;

    public function __construct(Work $work, User $user)
    {
        $this->work = $work;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('あなたのカテゴリに新しい仕事が登録されました')
                    ->view('emails.new_work_in_category')
                    ->with([
                        'work' => $this->work,
                        'user' => $this->user,
                    ]);
    }
}

