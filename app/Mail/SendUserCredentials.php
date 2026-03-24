<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendUserCredentials extends Mailable
{
    use Queueable, SerializesModels;
    public $username;
    public $password;
    public $fullname;

    /**
     * Create a new message instance.
     */
    public function __construct($username, $password, $fullname)
    {
        $this->username = $username;
        $this->password = $password;
        $this->fullname = $fullname;
    }

     public function build()
    {
        return $this->from('noreply@jfds.co.uk', 'Firedoor')
                ->subject('Your Firedoor Project Login Credentials')
                ->view('emails.credentials');
    }

}
