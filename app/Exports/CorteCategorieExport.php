<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Student;

class CorteCategorieExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $students = $this->get_registros();
        // $categorie = \DB::connection('categories_rv')->table('categories')
        //     ->where('id', $this->id)->first();
        return view('download.registros-corte', [
            'students' => $students,
            // 'categorie' => $categorie->categorie
        ]);
    }

    public function get_registros()
    {
        $students_ids = \DB::connection('categories_rv')
            ->table('students')->select('id')
            ->where('categorie_id', $this->id)->get();
        
        $ids = array();
        $students_ids->map(function($student) use(&$ids){
            array_push($ids,$student->id);
        }); 
        $students = Student::whereIn('id', $ids)
            ->withTrashed()->with('school', 'registros.folio')
            ->orderBy('created_at', 'desc')->get();
        
        return $students;
    }
}

