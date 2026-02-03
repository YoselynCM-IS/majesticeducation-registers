<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmailLog;

class EmailController extends Controller
{
    // OBTENER EMAILS POR ESTADO
    public function get_status(Request $request){
        $emails = EmailLog::where('status', $request->status)->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($emails);
    }

    // OBTENER EMAIL POR CORREO
    public function show(Request $request){
        $email = EmailLog::whereId($request->email_id)->with('student.codigos')->first();
        return response()->json($email);
    }

    // BUSCAR CORREOS POR COINCIDENCIA
    public function search(Request $request){
        $querySearch = $request->querySearch;
        $emails = EmailLog::where(function($query) use($querySearch){
                    $query->where('email', 'LIKE', '%'.$querySearch.'%')
                        ->orWhere('message_search', 'LIKE', '%'.$querySearch.'%');
                })->where('status', $request->status)->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($emails);
    }
}
