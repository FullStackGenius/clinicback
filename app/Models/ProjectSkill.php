<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSkill extends Model
{
    protected $fillable = ['project_id','skill_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
