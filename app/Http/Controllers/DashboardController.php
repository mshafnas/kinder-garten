<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('dashboard.index');
    }

    public function event(){
        return view('dashboard.events');
    }

    public function org(){
        return view('dashboard.org');
    }

    public function student(){
        $groups = Group::all();
        $html = '<option value="">Filter by group</option>';
        if (count($groups) > 0) {
            foreach ($groups as $key => $value) {
                $html .= '<option value="'.$value->id.'">'.$value->title.'</option>';
            }
        } else {
            '<option value="">No groups are found.</option>';
        }
        return view('dashboard.student')->with('groups', $html);
    }
}
