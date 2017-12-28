<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class UserVerf extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $token;
    
    public function __construct()
    {
        $this->token = Auth::user()->verf_token;
        $this->email = Auth::user()->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirm Email Address - '.config('app.name'))->view('emails.emailVerf', [
            'token' => $this->token,
            'email' => $this->email
        ]);
    }
}
