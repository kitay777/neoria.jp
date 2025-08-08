<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    protected $fillable = [
        'user_id', 'application_id', 'amount', 'type', 'description', 'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ★ ここを修正
// app/Models/PointLog.php
public function application()
{
    return $this->belongsTo(\App\Models\TimeProductApplication::class, 'application_id');
}


    // （おまけ）タイプを定数で持つとミス防止に役立ちます
    public const TYPE_APPLY  = 'apply';
    public const TYPE_BONUS  = 'bonus';
    public const TYPE_ADMIN  = 'admin';
    public const TYPE_REFUND = 'refund';
}
