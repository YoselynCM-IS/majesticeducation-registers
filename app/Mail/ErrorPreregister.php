<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorPreregister extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $error;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($error)
    {
        $this->error = $error;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(env('APP_NAME') == 'MAJESTIC EDUCATION'){
            $from = 'registro.pagos@majesticeducation-registers.com';
            $cc = "rp.majesticeducacion@gmail.com";
        } else {
            $from = 'registro.pagos@omegabook-registers.com';
            $cc = "rp.omegabook@gmail.com";
        }

        return $this->from($from)->bcc($cc)
        ->subject(__("Status Preregister"))
        ->markdown('mails.error-preresgister') //Template
        ->with('error', $this->error);
    }
}
