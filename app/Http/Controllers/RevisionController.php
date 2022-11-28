<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CorteCategorieExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Categorie;
use App\Registro;
use App\Student;

class RevisionController extends Controller
{
    public function index(){
        $students = Student::where('check', 'accepted')
                    ->where(function($query) {
                        $query->where('codes', true)
                                ->orWhere('delivery', true);
                    })
                    ->where('reviewed', false)
                    ->with('school')
                    ->orderBy('created_at', 'desc')->paginate(20);
        
        return response()->json($students);
    }

    // CREAR CATEGORIA
    public function save_categorie(Request $request){
        \DB::beginTransaction();
        try {
            $hoy = Carbon::now()->format('Y-m-d h:m:s');
            Categorie::create([
                    'categorie' => Str::of($request->categorie)->upper(),
                    'creado_por' => auth()->user()->name,
                    'created_at' => $hoy,
                    'updated_at' => $hoy
                ]);
            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json();
    }

    // ACTUALIZAR CATEGORIE
    public function update_categorie(Request $request){
        \DB::beginTransaction();
        try {
            $hoy = Carbon::now()->format('Y-m-d h:m:s');
            $categorie = Categorie::where('id', $request->id)->update([
                            'categorie' => Str::of($request->categorie)->upper(),
                            'creado_por' => auth()->user()->name,
                            'updated_at' => $hoy
                        ]);
            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json($categorie);
    }

    // ELIMINAR CATEGORIA
    public function delete_categorie(Request $request){
        \DB::beginTransaction();
        try {
            $categorie = Categorie::where('id', $request->categorie_id)->delete();
            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json(true);
    }

    // OBTENER CATEGORIAS
    public function show_categories(){ 
        $categories = Categorie::where('archivado', 0)
        ->orderBy('created_at', 'desc')->get();
        return response()->json($categories);
    }

    // MOVER REGISTROS
    public function save(Request $request){
        \DB::beginTransaction();
        try {
            $hoy = Carbon::now()->format('Y-m-d h:m:s');
            $categorie_id = $request->categorie_id;
            $selected = collect($request->selected);

            $selected->map(function($select) use($categorie_id, $hoy){
                $student = Student::find($select['id']);
                $student->update([
                    'categorie_id'  => $categorie_id,
                    'reviewed' => true, 
                    'date_reviewed' => $hoy
                ]);
                $student->delete();
            });

            Categorie::where('id', $categorie_id)
                ->update(['creado_por' => auth()->user()->name]);

            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json();
    }

    public function show(Request $request){
        $students = Student::where('check', 'accepted')
                    ->where('school_id', $request->school_id)
                    ->where(function($query) {
                        $query->where('codes', true)
                                ->orWhere('delivery', true);
                    })->where('reviewed', false)
                    ->with('school')
                    ->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($students);
    }

    public function by_categorie(Request $request){
        $students = Student::where('categorie_id', $request->categorie_id)
                    ->with('school')->withTrashed()
                    ->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($students);
    }

    public function by_student(Request $request){
        $students = Student::where('categorie_id', $request->categorie_id)
            ->where('name', 'like', '%'.$request->student.'%')
            ->withTrashed()->get();
        return response()->json($students);
    }

    public function show_student(Request $request){
        $student = Student::whereId($request->student_id)
                ->with('school')->withTrashed()
                ->paginate(1);
        return response()->json($student);
    }

    public function download_categorie($id){
        $hoy = Carbon::now()->format('Y-m-d');
        $nombre_archivo = $hoy.'-corte.xlsx';
        return Excel::download(new CorteCategorieExport($id), $nombre_archivo);
    }

    public function archive_categorie(Request $request){
        \DB::beginTransaction();
        try {
            Categorie::where('id', $request->categorie_id)
                ->update(['archivado' => 1]);
            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json(true);
    }
}
