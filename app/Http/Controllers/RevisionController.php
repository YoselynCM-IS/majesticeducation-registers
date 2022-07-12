<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CorteCategorieExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
            \DB::connection('categories_rv')->table('categories')
                ->insert([
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
            $categorie = \DB::connection('categories_rv')->table('categories')
                        ->where('id', $request->id)->update([
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
            $categorie = \DB::connection('categories_rv')->table('categories')
                        ->where('id', $request->categorie_id)->delete();
            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json(true);
    }

    // OBTENER CATEGORIAS
    public function show_categories(){
        $categories = \DB::connection('categories_rv')
            ->table('categories')->where('archivado', 0)
            ->orderBy('created_at', 'desc')->get();
        return response()->json($categories);
    }

    // MOVER REGISTROS
    public function save(Request $request){
        $selected = $request->selected;
        $categorie_id = $request->categorie_id;

        \DB::beginTransaction();
        try {
            \DB::connection('categories_rv')->table('categories')
                ->where('id', $categorie_id)
                ->update(['creado_por' => auth()->user()->name]);

            $hoy = Carbon::now()->format('Y-m-d h:m:s');
            foreach ($selected as $select) {
                $student = Student::find($select['id']);
                $student->update(['reviewed' => true, 'date_reviewed' => $hoy]);

                \DB::connection('categories_rv')->table('students')->insert([
                    'id'        => $student->id,    'categorie_id'  => $categorie_id,
                    'school_id' => $student->school_id, 
                    'name'      => $student->name,      'email' => $student->email,
                    'telephone' => $student->telephone, 'book' => $student->book, 
                    'quantity'  => $student->quantity,  'price' => $student->price, 
                    'total'     => $student->total,     'check' => $student->check, 
                    'delivery'  => $student->delivery,  'date_delivery' => $student->date_delivery,
                    'user_delivery' => $student->user_delivery, 'codes' => $student->codes, 
                    'date_codes'=> $student->date_codes,'user_codes' => $student->user_codes,
                    'validate'  => $student->validate,  'teacher' => $student->teacher, 
                    'group'     => $student->group,     'reviewed' => true, 
                    'date_reviewed' => $hoy, 'created_at'    => $hoy
                ]);

                $student->delete();
            }
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
        $students_ids = \DB::connection('categories_rv')
            ->table('students')->select('id')
            ->where('categorie_id', $request->categorie_id)->get();
        $ids = array();
        $students_ids->map(function($student) use(&$ids){
            array_push($ids,$student->id);
        });
        $students = Student::whereIn('id', $ids)
            ->withTrashed()->with('school')
            ->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($students);
    }

    public function by_student(Request $request){
        $students = \DB::connection('categories_rv')->table('students')
            ->where('categorie_id', $request->categorie_id)
            ->where('name', 'like', '%'.$request->student.'%')->get();
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
            \DB::connection('categories_rv')
                ->table('categories')->where('id', $request->categorie_id)
                ->update(['archivado' => 1]);
            \DB::commit();
        }  catch (Exception $e) {
            \DB::rollBack();
        }
        return response()->json(true);
    }
}
