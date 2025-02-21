<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsitePageContent extends Model
{
    protected $fillable = ['section_name', 'title', 'content', 'content_image', 'status'];

    public function getContentImagePathAttribute()
    {
        if (!empty($this->content_image)) {
            return asset('storage/content-image/' . $this->content_image);
        }
        return;
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends   = ['content_image_path'];
}
