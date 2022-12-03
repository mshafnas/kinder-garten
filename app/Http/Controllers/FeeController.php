<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Fee;
use App\Models\Student;

class FeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllFee(){
        $userType = auth()->user()->user_type;
        $userId = auth()->user()->userId;
        if(request()->ajax()){
            $data = DB::table('fees')
                        ->join('students', 'students.id', '=', 'fees.student_id')
                        ->join('groups', 'groups.id', '=', 'students.group_id')
                        ->select('fees.*', 'groups.title AS group_title', 'students.first_name AS first_name', 'students.last_name AS last_name', 'students.index_no AS index_no', 'students.group_id AS group_id')
                        ->orderBy('fees.id', 'desc')
                        ->get();
            
            return datatables()->of($data)
            ->addColumn('action', function($data){
                $button = '<button type="button" name="view" id="'.$data->id.'" class="view btn btn-outline-warning" onclick="updateFee('.$data->id.')">
                <i class="fas fa-pen"></i></button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-outline-danger" onclick="deleteFee('.$data->id.')">
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
                'student_id' => 'required',
                'month_year' => 'required',
                'amount' => 'required',
            ]);

            $month_year = explode('-', $request->month_year);
            $month = $month_year[1];
            $year = $month_year[0];

            $fee = new Fee;
            $fee->student_id = $request->student_id;
            $fee->fee_month = $month;
            $fee->fee_year = $year;
            $fee->amount = $request->amount;
            $fee->save();
            

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getFee(Request $request){
        try {
            $fee = Fee::find($request->id);
            $students = Student::all();
            if ($fee) {
                $studentHtml = '<option value="">Please select a student</option>';
                if (count($students) > 0) {
                    foreach ($students as $key => $value) {
                        if ($value->id == $fee->student_id) {
                            $studentHtml .= '<option value="'.$value->id.'" selected>'.$value->first_name.' '.$value->last_name.'</option>';
                        } else {
                            $studentHtml .= '<option value="'.$value->id.'">'.$value->first_name.' '.$value->last_name.'</option>';
                        }
                    }
                } else {
                    $studentHtml .= '<option value="">No groups are found.</option>';
                }
                $month = $fee->fee_month;
                $year = $fee->fee_year;
                $month_year = $year.'-'.$month;
                $html = '<input type="hidden" name="id" id="id" class="form-control" value="'.$fee->id.'" required>
                <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="student_id">Student</label>
                                <select name="student_id" id="student_id" class="form-control" required>
                                    '.$studentHtml.'
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="month_year">Month & Year</label>
                                <input type="month" name="month_year" id="month_year" class="form-control" value="'.$month_year.'" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control contact" value="'.$fee->amount.'" required>
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
                'student_id' => 'required',
                'month_year' => 'required',
                'amount' => 'required',
            ]);

            $month_year = explode('-', $request->month_year);
            $month = $month_year[1];
            $year = $month_year[0];

            $fee = Fee::find($request->id);
            $fee->student_id = $request->student_id;
            $fee->fee_month = $month;
            $fee->fee_year = $year;
            $fee->amount = $request->amount;
            $fee->save();
            

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function delete(Request $request){
        try {

            $fee = Fee::find($request->id);
            if ($fee) {
                $fee->delete();
                return response()->json(['error' => false, 'message' => 'success']);
            } else {
                return response()->json(['error' => true, 'message' => 'Invalid record id.']);
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
