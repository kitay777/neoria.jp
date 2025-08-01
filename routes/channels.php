<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{applicationId}', function ($user, $applicationId) {
    // このチャンネルを誰が購読できるかを定義（今は誰でもOK）
    return true;
});