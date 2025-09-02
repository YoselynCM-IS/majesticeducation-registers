<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    protected $fillable = [ 'id', 'school_id', 'referencia', 'tipo' ];
}
