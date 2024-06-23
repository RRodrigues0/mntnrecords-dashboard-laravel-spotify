<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendForgotMail extends Mailable
{
    public $firstname;
    public $password;

    public function __construct($firstname = '', $password = 'Error. Please try again!')
    {
        $this->firstname = $firstname;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Forgot your password')->view('mail.forgot', ['firstname' => $this->firstname, 'password' => $this->password]);
    }
}
