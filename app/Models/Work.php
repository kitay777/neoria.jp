<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    //
// app/Models/Work.php

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'price',
        'deadline',
        'is_overseas_allowed',
        'is_verified_by_client',
        'status',
        'image_path',
        'category_id',
        'execution_date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
