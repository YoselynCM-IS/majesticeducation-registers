<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'id', 'categorie', 'creado_por', 'archivado'
    ];
}
