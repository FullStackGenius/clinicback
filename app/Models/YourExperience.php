<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YourExperience extends Model
{
    
    protected $fillable = ['name', 'icon_image'];

    public function getIconImagePathAttribute()
    {
        return asset('storage/your-experience/' . $this->icon_image);
    }

    protected $appended  = ['icon_image_path'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
