<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    //
    protected $fillable =  ['contract_id', 'title', 'description', 'amount', 'completion_percentage', 'status','actual_payed_amount','platform_fee_charges'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function contract(){
        return $this->belongsTo(Contract::class,'contract_id','id');
    }
    public function milestonePayments()
    {
        return $this->hasOne(MilestonePaymentDetail::class);
    }
    public function trackMilestoneRequest()
    {
        return $this->hasOne(TrackMilestoneCompleteRequest::class);
    }

}
