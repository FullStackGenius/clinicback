<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilestonPayment extends Model
{
    //

    protected $fillable =  ['milestone_id', 'amount', 'payment_status', 'paid_at', 'transaction_id', 'status'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
