<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable =  ['user_one_id', 'user_two_id','contract_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
