<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Confirmation extends Mailable
{
    use Queueable, SerializesModels;
    
    private $confirmation_code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($confirmation_code)
    {
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $confirmation_code = $this->confirmation_code;
        return $this->view('mail.confirm', compact('confirmation_code'));
    }
}
