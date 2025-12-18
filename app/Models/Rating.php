<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable =  ['rating_by', 'contract_id','review','rating_number','rating_to'];
    protected $hidden = [
        // 'created_at',
        'updated_at',
    ];

    public function ratingToUser(){
        return $this->belongsTo(User::class,'rating_to');
    }
    public function ratingByUser(){
        return $this->belongsTo(User::class,'rating_by');
    }

    public function contractDetail(){
        return $this->belongsTo(Contract::class,'contract_id');
    }
    public function ratingReply(){
        return $this->hasOne(RatingReply::class);
    }
}
