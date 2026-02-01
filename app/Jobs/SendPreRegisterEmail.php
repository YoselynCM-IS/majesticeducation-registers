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

    protected Student $student;
    protected string $message;

    public function __construct(Student $student, string $message)
    {
        $this->student = $student;
        $this->message = $message;
    }

    public function handle(): void
    {
        Mail::to($this->student->email)
            ->send(new PreRegister($this->message, $this->student));

        EmailLog::create([
            'email'    => $this->student->email,
            'subject'  => 'Respuesta de pre-registro',
            'mailable' => PreRegister::class,
            'payload'  => json_encode([
                'student_id' => $this->student->id,
            ]),
            'sent_at'  => now(),
        ]);
    }
}
