<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('dashboard.student');
    }
}
