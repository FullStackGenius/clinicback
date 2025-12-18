<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingReply extends Model
{
      protected $fillable =  ['rating_id', 'reply_by','reply_to','comments','rating_to','created_at', 'updated_at'];
     
    protected $hidden = [
        // 'created_at',
        'updated_at',
    ];
    public function replyBy(){
        return $this->belongsTo(User::class,'reply_by');
    }
    public function replyTo(){     
        return $this->belongsTo(User::class,'reply_to');
    }
}
