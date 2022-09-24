<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllEvents(){
        $userType = auth()->user()->user_type;
        $userId = auth()->user()->userId;
        if(request()->ajax()){
            $data = '';
            if ($userType == "admin") {
                $data = DB::table('users')
                        ->join('events', 'users.id', '=', 'events.user_id')
                        ->select('events.*', 'users.name AS created_by')
                        ->orderBy('events.id', 'desc')
                        ->get();
            } else {
                $data = DB::table('users')
                        ->join('events', 'users.id', '=', 'events.user_id')
                        ->select('events.*', 'users.name AS created_by')
                        ->where('events.user_id', '=', $userId)
                        ->orderBy('events.id', 'desc')
                        ->get();
            }
            return datatables()->of($data)
            ->addColumn('action', function($data){
                $button = '<button type="button" name="view" id="'.$data->id.'" class="view btn btn-outline-primary">
                <i class="fas fa-eye"></i></button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-outline-danger">
                <i class="fas fa-trash"></i></button>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

    }

    // add new events
    public function create(Request $request){
        try {
            $this->validate($request, [
                'title' => 'required',
                'date' => 'required',
            ]);

            $event = new Event;
            $event->title = $request->title;
            $event->date = $request->date;
            $event->time = $request->time;
            $event->venue = $request->venue ? $request->venue : null;
            $event->user_id = auth()->user()->id;
            $event->save();
            
            // handle file upload 
            // check user has selected file/files
            if($request->hasFile('pics')){
                foreach ($request->file('pics') as $image) {
                        $picture = new EventImage;
                        //Get file name with extension
                        $fileNameWithExt = $image->getClientOriginalName();
                        
                        // Get only file name
                        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                        // Get only the extension
                        $fileExt = $image->getClientOriginalExtension();

                        // file name to store
                        $fileNameToStore =  Carbon::now()->timestamp.rand(10,100).'.'.$fileExt;

                        $finalPath = 'public/events/'.$request->title.'/';

                        // check directory exists
                        if (!Storage::exists($finalPath)) {
                            Storage::makeDirectory($finalPath);
                        }
                        
                        // upload image
                        Storage::put($finalPath. $fileNameToStore, fopen($image, 'r+'));
                        
            
                        //Resize image here
                        // $path = public_path('storage/events/'.$fileNameToStore);
                        // $img = Image::make($path)->resize(400, 278)->save($path);

                        $picture->file_name = $fileNameToStore;
                        $picture->event_id = $event->id;
                        $picture->save();
                }
            }

            return response()->json(['error' => false, 'message' => 'success']);

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
