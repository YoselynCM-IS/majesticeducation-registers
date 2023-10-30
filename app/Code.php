<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Code extends Model
{
    protected $fillable = [
        'student_id', 'editorial', 'code1', 'code2', 'code3'
    ];

    public function student(){
        return $this->belongsTo(Student::class)->withTrashed();
    }
}
