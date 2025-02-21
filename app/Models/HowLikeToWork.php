<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HowLikeToWork extends Model
{
    protected $fillable = ['name', 'icon_image','description'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getIconImagePathAttribute()
    {
        return asset('storage/how-to-like-work/' . $this->icon_image);
    }
    protected $appended = ['icon_image_path'];
}
