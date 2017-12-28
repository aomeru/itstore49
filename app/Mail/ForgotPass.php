<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ForgotPass extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $token;
    protected $username;
    
    public function __construct($email,$token,$username)
    {
        $this->token = $token;
        $this->email = $email;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Password - '.config('app.name'))->view('emails.preset', [
            'token' => $this->token,
            'email' => $this->email,
            'username' => $this->username
        ]);
    }
}
