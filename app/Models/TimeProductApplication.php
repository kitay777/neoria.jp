<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TimeProductApplication extends Model
{
    protected $fillable = [
        'time_product_id',
        'user_id',
        'application_uuid',
        'status',        // 'pending','paid','cancelled' など運用で
        'message',       // 申請時のメッセージがあれば
        'price_snapshot' // 申請時の価格保存（将来の価格改定対策）
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // UUID 自動採番（毎回新規の申請ごとにユニーク）
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->application_uuid)) {
                $model->application_uuid = (string) Str::uuid();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(TimeProduct::class, 'time_product_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    // app/Models/TimeProductApplication.php
    public function pointLogs()
    {
        return $this->hasMany(PointLog::class, 'application_id');
    }
    // app/Models/TimeProductApplication.php
    public function timeProduct()
    {
        return $this->belongsTo(\App\Models\TimeProduct::class, 'time_product_id');
    }

    // （念のため）逆参照

    // app/Models/PointLog.php
    public function application()
    {
        return $this->belongsTo(\App\Models\TimeProductApplication::class, 'application_id');
    }

    public function work()
    {
        return $this->belongsTo(\App\Models\Work::class, 'work_id');
    }



}
