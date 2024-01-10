<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SorterController extends Controller
{
    // MOSTRAR LOS CORTES CREADOS
    public function list_categories(){
        return view('roles.sorter.categories.categories');
    }
}
