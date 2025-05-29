<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilestonePaymentDetail extends Model
{
    protected $fillable =  ["milestone_id", "contract_id", "freelancer_id", "project_id", "transfer_id", "destination_account", "destination_payment", "balance_transaction_id", "currency", "amount", "transfer_group", "reversed", "transferred_at", "raw_data", "actual_milestone_amount", "platform_fee_charges"];

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function transaction()
    {
        return $this->morphOne(AllPaymentTransaction::class, 'payable');
    }
}
