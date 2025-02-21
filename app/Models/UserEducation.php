<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    protected $fillable = [
        'user_id',
        'school',
        'degree',
        'field_of_study',
        'start_date_attended',
        'end_date_attended',
        'description'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
