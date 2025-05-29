<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceData extends Model
{
    protected $fillable = [
        'resource_category_id','title', 'short_description', 'description', 'resource_image', 'status'
    ];

    public function resourceCategory()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id');
    }
    public function getResourceImagePathAttribute()
    {
        return asset('storage/resource-image/' . $this->resource_image);
    }
    protected $appends = ['resource_image_path'];
}
