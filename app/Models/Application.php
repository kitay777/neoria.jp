<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'work_id',
        'user_id',
        'status',
        'message',
        'offer_price',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pointLog()
    {
        return $this->hasOne(PointLog::class);
    }
    // app/Models/Application.php
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

}
