<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class EmailLog extends Model
{
    protected $fillable = [
        'student_id',
        'email',
        'subject',
        'message',
        'message_search',
        'mailable',
        'sent_at',
        'status', 
        'error'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'payload' => 'array',
    ];

    public function student(){
        return $this->belongsTo(Student::class)->withTrashed();
    }
}
