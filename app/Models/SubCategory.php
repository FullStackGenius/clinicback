<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    protected $fillable = ['name', 'subcategory_status','category_id','slug'];
    protected $hidden = [
        'created_at',
        'updated_at',
         'pivot'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subcategory', 'sub_category_id', 'user_id');
    }

    public function getCategory()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public static function boot()
    {
        parent::boot();

        // Create the slug when a new Skill is created
        static::creating(function ($subcategory) {
            $subcategory->slug = Str::slug($subcategory->name);
        });

        // Update the slug when the Skill name is changed
        static::updating(function ($subcategory) {
            $subcategory->slug = Str::slug($subcategory->name);
        });
    }
}
