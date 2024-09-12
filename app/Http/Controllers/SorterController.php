<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Student;

class SorterController extends Controller
{
    // MOSTRAR INICIO DEL LISTADO DE ALUMNOS
    public function home(){
        $hoy = Carbon::now()->format('Y-m-d');
        $students = Student::with('school')
            ->where('created_at', 'like', '%'.$hoy.'%')->orderBy('created_at', 'desc')->get();
        
        return view('roles.sorter.home', compact('students'));
    }

    // MOSTRAR LOS CORTES CREADOS
    public function list_categories(){
        return view('roles.sorter.categories.categories');
    }
}
