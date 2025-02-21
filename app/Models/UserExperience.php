<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'title',
        'company',
        'location',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'currently_working',
        'country_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getCountryNameAttribute()
    {
        if (!empty($this->country_id)) {
            return Country::find($this->country_id)->name;
        }
        return;
    }

    protected $appends = ['country_name'];
}
