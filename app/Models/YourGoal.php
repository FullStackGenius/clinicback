<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YourGoal extends Model
{
    protected $fillable = ['name', 'icon_image','status'];

    public function getIconImagePathAttribute()
    {
        return asset('storage/your-goal/' . $this->icon_image);
    }

    protected $appended = ['icon_image_path'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
