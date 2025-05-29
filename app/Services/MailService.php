<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Safely send an email with logging and error handling.
     *
     * @param string|null $email
     * @param \Illuminate\Mail\Mailable $mailable
     * @return void
     */
    public function safeSend(?string $email, $mailable,$message)
    {
        try {
            if ($email) {
                Mail::to($email)->send($mailable);
            } 
        } catch (\Exception $e) {
            Log::channel('daily')->info('login Api log '.$message.' \n: ' . $e->getMessage());
           
        }
    }
}
