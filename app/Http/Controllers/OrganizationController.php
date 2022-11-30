<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllOrg(){
        $userType = auth()->user()->user_type;
        $userId = auth()->user()->userId;
        if(request()->ajax()){
            $data = '';
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
            $data = DB::table('organizations')
                        ->orderBy('organizations.id', 'desc')
                        ->get();
            return datatables()->of($data)
            ->addColumn('action', function($data){
                $button = '<button type="button" name="view" id="'.$data->id.'" class="view btn btn-outline-warning" onclick="updateOrg('.$data->id.')">
                <i class="fas fa-pen"></i></button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-outline-danger" onclick="deleteOrg('.$data->id.')">
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
                'name' => 'required',
                'email' => 'required',
            ]);

            $org = new Organization;
            $org->name = $request->name;
            $org->email = $request->email;
            $org->save();
            

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function delete(Request $request){
        try {

            $org = Organization::find($request->id);
            if ($org) {
                $org->delete();
                return response()->json(['error' => false, 'message' => 'success']);
            } else {
                return response()->json(['error' => true, 'message' => 'Invalid record id.']);
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getOrg(Request $request){
        try {
            $org = Organization::find($request->id);
            if ($org) {
                $html = '<input type="hidden" name="id" id="id" class="form-control" value="'.$org->id.'" required>
                <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="'.$org->name.'" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="'.$org->email.'" required>
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
                'id' => 'required',
                'name' => 'required',
                'email' => 'required',
            ]);

            $org = Organization::find($request->id);
            $org->name = $request->name;
            $org->email = $request->email;
            $org->save();
            

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
