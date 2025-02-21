<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable =  ['user_id', 'contract_id','review','rating_number'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
