<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreRegister extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $message;
    private $student;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $student)
    {
        $this->message = $message;
        $this->student = $student;
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

        return $this->from($from)
            ->cc($from)
            ->subject(__("Respuesta de pre-registro"))
            ->markdown('mails.save-pre-register') //Template
            ->with('student', $this->student)
            ->with('message', $this->message);
    }
}
