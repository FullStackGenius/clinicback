<?php 

// app/Services/StripeService.php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Account;
use Stripe\Transfer;
class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCustomAccount($email)
    {
        return Account::create([
            'type' => 'custom',
            'country' => 'US',
            'email' => $email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);
    }

    public function generateAccountLink($accountId, $refreshUrl, $returnUrl)
    {
        return \Stripe\AccountLink::create([
            'account' => $accountId,
            'refresh_url' => $refreshUrl,
            'return_url' => $returnUrl,
            'type' => 'account_onboarding',
        ]);
    }

    // public function transferToFreelancer($amount, $accountId, $transferGroup)
    // {
    //     return Transfer::create([
    //         'amount' => $amount * 100,
    //         'currency' => 'usd',
    //         'destination' => $accountId,
    //         'transfer_group' => $transferGroup,
    //     ]);
    // }


    
}
