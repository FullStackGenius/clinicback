<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageProficiency extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function userLanguages()
    {
        return $this->hasMany(UserLanguage::class, 'language_proficiency_id');
    }
}
