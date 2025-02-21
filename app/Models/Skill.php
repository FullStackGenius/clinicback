<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Skill extends Model
{
   // use HasFactory, SoftDeletes;
   use HasFactory;
 
   protected $hidden = [
    'created_at',
    'updated_at',
    'pivot'
];
    protected $fillable = ['name', 'status','skill_slug'];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_skills');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills');
    }


    public static function boot()
    {
        parent::boot();

        // Create the slug when a new Skill is created
        static::creating(function ($skill) {
            $skill->skill_slug = Str::slug($skill->name);
        });

        // Update the slug when the Skill name is changed
        static::updating(function ($skill) {
            $skill->skill_slug = Str::slug($skill->name);
        });
    }
}
