<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable =  ['name', 'status'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_languages')
            ->withPivot('language_proficiency_id') // Correct pivot field name
            ->withTimestamps();
    }
}
