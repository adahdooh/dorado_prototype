<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advice\Advice;
use App\Models\Appointment\PatientReserve;
use App\Models\Diabetes\Diabetes;
use App\Models\Meal\Meal;
use App\Models\Goal\Goal;
use  App\Models\Water\Water;
use App\Models\Activity\Activity;
use App\Models\Medicine\PatientMedicine;
use App\Models\PatientBody\PatientBody;
use App\Notifications\SendNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Liliom\Unifonic\UnifonicFacade;
use MacsiDigital\Zoom\Facades\Zoom;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 200,
            'data' => User::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function next_interview(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'interview_duration' => 'required',
            'next_user_id' => 'nullable'
        ]);

        if ($validator->fails()) {
            $response = ['data' => $validator->errors(), 'status' => false];
            return response()->json($response, 422);
        }

        if ($request->get('user_id') != 0){
            $user = User::find($request->get('user_id'));
            $user->interview_duration = $request->get('interview_duration');
            $user->save();
        }
        
        if ($request->get('interview_duration') != null){
            $next_user = User::find($request->get('next_user_id'));
            if ($next_user != null && $next_user->fcm_token != null){
                $next_user->notify(new SendNotification(null, 'Call', 'test', 'Call'));
            }
        }

        $response = ['status' => true];
     
        return response()->json($response, 200);
    }
}