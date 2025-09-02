<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Referencia;
use App\Student;
use App\Book;

class School extends Model
{
    protected $fillable = [ 'id', 'name' ];
    use SoftDeletes;

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function books(){
        return $this->belongsToMany(Book::class)->withPivot('price');
    }

    public function referencias(){
        return $this->hasMany(Referencia::class);
    }
}
