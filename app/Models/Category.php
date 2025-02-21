<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'category_status','slug'];
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    public function subCategories(){
        return $this->hasMany(SubCategory::class);
    }

    public static function boot()
    {
        parent::boot();

        // Create the slug when a new Skill is created
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        // Update the slug when the Skill name is changed
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }
}
