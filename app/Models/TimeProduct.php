<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

use Intervention\Image\Drivers\Gd\Driver;


class TimeProduct extends Model
{
    use HasFactory;

    protected $casts = [
        'trade_types' => 'array',
    ];

    protected $fillable = [
        'user_id','title','description','image_path','price','duration',
        'category_id','is_active','trade_types','prefecture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function applications()
    {
        return $this->hasMany(\App\Models\TimeProductApplication::class, 'time_product_id');
    }




}
