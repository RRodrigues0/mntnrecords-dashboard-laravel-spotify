<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendStatisticMail extends Mailable
{
    public $subject;
    public $message;
    public $informations;

    public function __construct($subject = '', $message = '', $informations = '')
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->informations = $informations;
    }

    public function build()
    {
        date_default_timezone_set('Europe/Berlin');
        setlocale(LC_TIME, "de_DE");
        $date = strftime('%A, %e. %B %G - %H:%M:%S');

        return $this->subject($this->subject)->view('mail.debug', ['text' => $this->message . $this->informations, 'date' => $date]);
    }
}
