<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['project_id', 'freelancer_id', 'started_at', 'ended_at', 'type', 'amount', 'status', 'payment_type','proposal_id','client_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function proposal(){
        // return $this->belongsTo(User::class,'freelancer_id','id');
        return $this->belongsTo(Proposal::class,'proposal_id','id');
    }

    public function project(){
        // return $this->belongsTo(User::class,'freelancer_id','id');
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function freelancer(){
        // return $this->belongsTo(User::class,'freelancer_id','id');
        return $this->belongsTo(User::class,'freelancer_id','id');
    }

    public function client(){
        // return $this->belongsTo(User::class,'freelancer_id','id');
        return $this->belongsTo(User::class,'client_id','id');
    }

    public function contractPayment()
    {
        return $this->hasOne(ContractPayment::class);
    }
    
}
