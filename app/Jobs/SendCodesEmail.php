<?php

namespace App\Jobs;

use App\Mail\SendCodes;
use App\EmailLog;
use App\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCodesEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Student $student;
    protected string $code1;
    protected string $code2;
    protected string $code3;
    protected string $code4;
    protected string $code5;
    protected string $editorial;

    // $code->student->name, $code->code1, $code->code2, $code->code3, $code->code4, $code->code5, $code->student->book, $code->editorial
    public function __construct(Student $student, string $code1, string $code2, string $code3, string $code4, string $code5, string $editorial)
    {
        $this->student = $student;
        $this->code1 = $code1;
        $this->code2 = $code2;
        $this->code3 = $code3;
        $this->code4 = $code4;
        $this->code5 = $code5;
        $this->editorial = $editorial;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->student->email)->queue(new SendCodes($this->student->name, $this->code1, $this->code2, $this->code3, $this->code4, $this->code5, $this->student->book, $this->editorial));        
        
        $data = [
            'message' => $this->editorial,
            'name' => $this->student->name,
            'book' => $this->student->book
        ];

        EmailLog::create([
        'student_id' => $this->student->id,    
        'email'    => $this->student->email,
            'subject'  => 'Código',
            'mailable' => SendCodes::class,
            'message'  => json_encode($data),
            'message_search' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'sent_at'  => now(),
        ]);
    }
}
