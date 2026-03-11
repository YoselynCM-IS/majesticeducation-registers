<?php

namespace App\Jobs;

use App\Mail\PreRegister;
use App\EmailLog;
use App\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPreRegisterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;    // Máximo 2 intentos antes de marcar como fallido
    public $backoff = 60; // Si falla, espera 60 segundos para el segundo intento

    protected $student;
    protected $message;

    public function __construct(Student $student, string $message)
    {
        $this->student = $student;
        $this->message = $message;
    }

    public function handle(): void
    {
        // 1. ESPERA DE SEGURIDAD (2 segundos)
        // Esto evita saturar el límite por minuto de Hostgator
        sleep(2); 

        // 2. Verificar si ya fue enviado (Doble validación de seguridad)
        $s = Student::find($this->student->id);
        if (!$s || $s->validate === 'ENVIADO') {
            return; 
        }

        // 3. Validar email
        if (!filter_var($this->student->email, FILTER_VALIDATE_EMAIL)) {
            $this->create_emaillog(null, 'failed', 'Email inválido');
            $s->update(['validate' => 'NO ENVIADO']);
            return; // Usamos return en lugar de Exception para no reintentar algo que nunca funcionará
        }

        try {
            Mail::to($this->student->email)->send(new PreRegister($this->message, $this->student));
            
            $this->create_emaillog(now(), 'sent', null);
            $s->update(['validate' => 'ENVIADO']);

        } catch (\Throwable $e) {
            $this->create_emaillog(null, 'failed', $e->getMessage());
            $s->update(['validate' => 'NO ENVIADO']);
            throw $e; // Importante → manda el job a failed_jobs
        }
    }

    public function create_emaillog($sent_at, $status, $error){
        $data = [
            'message' => $this->message,
            'name' => $this->student->name,
            'book' => $this->student->book
        ];

        EmailLog::create([
            'student_id' => $this->student->id,
            'email'    => $this->student->email,
            'subject'  => 'Respuesta de pre-registro',
            'message'  => json_encode($data),
            'message_search' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'mailable' => PreRegister::class,
            'sent_at'  => $sent_at,
            'status'   => $status,
            'error'    => $error,
        ]);
    }
}
