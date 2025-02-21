<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
   
    protected $fillable = [
        'project_id',
        'bid_amount',
        'cover_letter',
        'freelancer_id'

    ];

    public function freelancerUser(){
        return $this->belongsTo(User::class,'freelancer_id','id');
    }

    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }


    public function contract()
    {
        return $this->hasOne(Contract::class, 'proposal_id', 'id');
    }
}
