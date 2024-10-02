<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Registro;

class Folio extends Model
{
    protected $fillable = [ 'fecha', 'concepto', 'abono', 'saldo', 'occupied', 'marcado_por' ];

    public function registros(){
        return $this->hasMany(Registro::class);
    }
}
