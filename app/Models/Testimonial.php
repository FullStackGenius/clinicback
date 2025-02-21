<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'designation', 'client_image', 'feedback'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getClientImagePathAttribute()
    {
        return asset('storage/testimonial-image/' . $this->client_image);
    }
    protected $appends = ['client_image_path'];
}
