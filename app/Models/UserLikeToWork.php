<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLikeToWork extends Model
{
    protected $fillable = ['user_id', 'how_like_to_work_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function howLikeTowork(){
        return $this->belongsTo(HowLikeToWork::class,'how_like_to_work_id','id');
    }
}
