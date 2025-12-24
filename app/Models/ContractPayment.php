<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    protected $fillable = ["contract_id", "project_id", "client_id", "payment_intent_id", "amount", "currency", "status", "paid_at", "transfer_group", "stripe_response","contract_detail_pdf"];

    public function transaction()
    {
        return $this->morphOne(AllPaymentTransaction::class, 'payable');
    }
}
