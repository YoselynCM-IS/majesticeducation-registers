<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Exports\DayExport;
use Excel;

class SendRegisters extends Mailable
{
    use Queueable, SerializesModels;

    private $lista;
    private $day;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lista, $day)
    {
        $this->lista = $lista;
        $this->day = $day;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fecha = $this->day->format('d-m-Y h:i');
        $nombre = $fecha.'-registros'.'.xlsx';

        if(env('APP_NAME') == 'MAJESTIC EDUCATION'){
            // $from = 'registro.pagos@majesticeducation-registers.com';
            $cc = "rp.majesticeducacion@gmail.com";
        } else {
            // $from = 'registro.pagos@omegabook-registers.com';
            $cc = "rp.omegabook@gmail.com";
        }

        return $this->from($cc)
            // ->cc($cc)
            ->subject(__($fecha))
            ->attachData($this->lista, $nombre)
            ->markdown('mails.send-registers')
            ->with('fecha', $fecha);
    }
}
