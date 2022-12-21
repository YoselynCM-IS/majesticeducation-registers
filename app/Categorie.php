<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'id', 'categorie', 'school_id', 'creado_por', 'archivado'
    ];
}
