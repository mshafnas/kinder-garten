<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllStudents(){
        $userType = auth()->user()->user_type;
        $userId = auth()->user()->userId;
        if(request()->ajax()){
            $data = DB::table('students')
                        ->join('groups', 'groups.id', '=', 'students.group_id')
                        ->select('students.*', 'groups.title AS group_title')
                        ->orderBy('students.id', 'desc')
                        ->get();
            // if ($userType == "admin") {
            //     $data = DB::table('organizations')
            //             ->orderBy('events.id', 'desc')
            //             ->get();
            // } else {
            //     $data = DB::table('users')
            //             ->join('events', 'users.id', '=', 'events.user_id')
            //             ->select('events.*', 'users.name AS created_by')
            //             ->where('events.user_id', '=', $userId)
            //             ->orderBy('events.id', 'desc')
            //             ->get();
            // }
            
            return datatables()->of($data)
            ->addColumn('action', function($data){
                $button = '<button type="button" name="view" id="'.$data->id.'" class="view btn btn-outline-warning" onclick="updateStudent('.$data->id.')">
                <i class="fas fa-pen"></i></button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-outline-danger" onclick="deleteStudent('.$data->id.')">
                <i class="fas fa-trash"></i></button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

    }
}
