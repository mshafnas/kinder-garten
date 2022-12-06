<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Group;

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

    public function create(Request $request){
        try {
            $this->validate($request, [
                'group_id' => 'required',
                'index_no' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'dob' => 'required',
                'age' => 'required',
                'contact_no' => 'required',
                'whatsapp_no' => 'required',
                'address' => 'required',
            ]);

            $student = new Student;
            $student->group_id = $request->group_id;
            $student->index_no = $request->index_no;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->dob = $request->dob;
            $student->age = $request->age;
            $student->contact_no = $request->contact_no;
            $student->whatsapp_no = $request->whatsapp_no;
            $student->address = $request->address;
            $student->save();
            

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getStudent(Request $request){
        try {
            $student = Student::find($request->id);
            $groups = Group::all();
            if ($student) {
                $groupHtml = '<option value="">Select group</option>';
                if (count($groups) > 0) {
                    foreach ($groups as $key => $value) {
                        if ($value->id == $student->group_id) {
                            $groupHtml .= '<option value="'.$value->id.'" selected>'.$value->title.'</option>';
                        } else {
                            $groupHtml .= '<option value="'.$value->id.'">'.$value->title.'</option>';
                        }
                    }
                } else {
                    $groupHtml .= '<option value="">No groups are found.</option>';
                }
                $html = '<input type="hidden" name="id" id="id" class="form-control" value="'.$student->id.'" required>
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Group</label>
                                <select name="group_id" id="group_id" class="form-control" required>
                                    '.$groupHtml.'
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="index_no">Index Number</label>
                                <input type="text" name="index_no" id="index_no" class="form-control" value="'.$student->index_no.'" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="'.$student->first_name.'" required>
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="'.$student->last_name.'" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="form-control" value="'.$student->dob.'" required>
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="text" name="age" id="age" class="form-control" value="'.$student->age.'" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_no">Contact No</label>
                                <input type="number" name="contact_no" id="edit_contact_no" class="form-control" value="'.$student->contact_no.'" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp_no">Whatsapp No</label>
                                <input type="number" name="whatsapp_no" id="edit_whatsapp_no" class="form-control" value="'.$student->whatsapp_no.'" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address" class="form-control" value="'.$student->address.'" required>
                            </div>
                        </div>
                    </div>';
                return $html;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function update(Request $request){
        try {
            $this->validate($request, [
                'group_id' => 'required',
                'index_no' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'dob' => 'required',
                'age' => 'required',
                'contact_no' => 'required',
                'whatsapp_no' => 'required',
                'address' => 'required',
            ]);

            $student = Student::find($request->id);
            $student->group_id = $request->group_id;
            $student->index_no = $request->index_no;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->dob = $request->dob;
            $student->age = $request->age;
            $student->contact_no = $request->contact_no;
            $student->whatsapp_no = $request->whatsapp_no;
            $student->address = $request->address;
            $student->save();
            

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function delete(Request $request){
        try {

            $student = Student::find($request->id);
            if ($student) {
                $student->delete();
                return response()->json(['error' => false, 'message' => 'success']);
            } else {
                return response()->json(['error' => true, 'message' => 'Invalid record id.']);
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
