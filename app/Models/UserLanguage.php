<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    protected $fillable = [
        'user_id',
        'language_id',
        'language_proficiency_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function proficiency()
    {
        return $this->belongsTo(LanguageProficiency::class, 'language_proficiency_id');
    }

    public function getLanguageNameAttribute()
    {
        if (!empty($this->language_id)) {
            return Language::find($this->language_id)->name;
        }
        return;
    }
    public function getLanguageProficienceyNameAttribute()
    {
        if (!empty($this->language_id)) {
            return LanguageProficiency::find($this->language_proficiency_id)->name;
        }
        return;
    }
    protected $appends = ['language_name','Language_proficiencey_name'];
}
