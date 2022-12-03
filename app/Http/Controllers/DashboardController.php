<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $studentCount = Student::count();
        return view('dashboard.index')->with('student', $studentCount);
    }

    public function event(){
        return view('dashboard.events');
    }

    public function org(){
        return view('dashboard.org');
    }

    public function student(){
        $groups = Group::all();
        $html = '<option value="">Select group</option>';
        if (count($groups) > 0) {
            foreach ($groups as $key => $value) {
                $html .= '<option value="'.$value->id.'">'.$value->title.'</option>';
            }
        } else {
            $html .= '<option value="">No groups are found.</option>';
        }
        return view('dashboard.student')->with('groups', $html);
    }

    public function fee(){
        $students = Student::orderBy('id', 'DESC')->get();
        $html = '<option value="">Please select a student</option>';
        if (count($students) > 0) {
            foreach ($students as $key => $value) {
                $html .= '<option value="'.$value->id.'">'.$value->first_name.' '.$value->last_name.'</option>';
            }
        } else {
            $html .= '<option value="">No students are found.</option>';
        }
        return view('dashboard.fee')->with('students', $html);
    }
}
