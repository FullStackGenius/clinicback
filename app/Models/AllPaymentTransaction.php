<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllPaymentTransaction extends Model
{
    
    protected $fillable = ["payer_id", "receiver_id", "payment_for", "payable_type", "payable_id",];

    public function payable()
    {
        return $this->morphTo();
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
