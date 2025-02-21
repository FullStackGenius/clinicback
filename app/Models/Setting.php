<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['website_logo', 'facebook_link', 'instagram_link', 'twitter_link', 'linkedin_link'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function getWebsiteLogoPathAttribute()
    {
        return asset('storage/website-logo/' . $this->website_logo);
    }

    protected $appended  = ['website_logo_path'];
}
