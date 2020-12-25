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

        $user = User::find($request->get('user_id'));
        $user->interview_duration = $request->get('interview_duration');
        $user->save();

        if ($request->get('interview_duration') != null)
        {
            $next_user = User::find($request->get('next_user_id'));
            if ($next_user != null && $next_user->fcm_token != null){
                $next_user->notify(new SendNotification(null, $request->title, null, $request->message));
            }
        }

        $response = ['status' => true];
     
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add new dietitian
        $validator = Validator::make($request->all(), [
            // 'phone' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'status' => false], 422);
        }
		
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->merge(['role' => 'dietitian', 'status'=> 'accepted'])->toArray());
        $response = ['data' => $user, 'message' => 'success', 'status' => true];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|numeric',
            'gender' => 'required',
            'weight' => 'numeric',
            'length' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }
		
        $user = User::find(auth('api')->user()->id);

        if (!$user) {
            $response = ['message' => 'User does not exist', 'status' => false];
            return response()->json($response, 422);
        }
		
        $user = tap($user)->update($request->toArray());
        $fcm_token = '';
        
		if ($request->route()->getAction('as') == 'register.continue.user') {
            $fcm_token = $request->fcm_token ?? null;
            $user->fcm_token = $fcm_token;
            $user->save();
            $today = Carbon::today();
            $body = PatientBody::create($request->merge(['date' => $today, 'user_id' => auth('api')->user()->id])->toArray());
        }

        $response = ['data' => $user,'fcm_token'=>$fcm_token, 'message' => 'success', 'status' => true];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth('api')->user()->role == 'admin') {
            $user = User::find($id)->delete();
            $response = ['data' => $user, 'message' => 'success', 'status' => 200];
            return response()->json($response, 200);
        } else {
            $response = ['data' => 'no permissions', 'message' => 'fail', 'status' => false];
            return response()->json($response, 422);
        }
    }

    public function userNotificationChange(Request $request)
    {
        $user = User::find(auth('api')->user()->id);
        // 0=off, 1=on
        $user->notification = (int)$request->status;
        $user->save();
        $response = ['data' => $user, 'message' => 'success', 'status' => true];

        return response()->json($response, 200);
    }

    public function userPasswordChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }
        if ($request->has('phone')) {
            return $this->changePasswordByPhone($request);
        } else {
            return $this->changePasswordByAuthId($request);
        }
    }

    public function userMeetingHistory(Request $request)
    {
                $meetings = auth('api')->user()->meetings()->with(['patient', 'dietitian'])->get();

        $response = ['data' => $meetings, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function has_active_session(Request $request)
    {
        $result = auth('api')->user()->meetings->where('status', 'waiting_patient')->count();
        $response = ['data' => [ 
                        'result' => $result != 0,
                        'join_url' => $result == 0 ? null : auth('api')->user()->meetings->where('status', 'waiting_patient')->first()->join_url,
                    ], 
                    'message' => 'success', 'status' =>true ];
        return response()->json($response, 200);
    }

    public function userPasswordChangeForget(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }
        if ($request->has('phone')) {
            return $this->changePasswordByPhone($request);
        } else {
            return $this->changePasswordByAuthId($request);

        }

    }

    public function sendOtpCode(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^(05)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }

        $otp_code = mt_rand(1000, 9999);
        $user = User::where('phone', ltrim($request->phone, $request->phone[0]))->get()->first();

        if ($user) {
            $user = User::find($user->id);
            $user->otp_code = $otp_code;
            $user->save();

            //send code to phone
            $sending_message = UnifonicFacade::send('966'.$user->phone, 'Your liven code is ' . $otp_code, 'LIVEN-SA');


            $response = ['data' => 'otp should be sent to mobile', 'otp_code' => $otp_code, 'message' => 'success', 'status' => true];
            return response()->json($response, 200);
        } else {
            $response = ['data' => 'user not found', 'message' => 'fail', 'status' => false];
            return response()->json($response, 404);
        }
    }

    public function verifyUserOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }

        $request->phone = ltrim($request->phone, $request->phone[0]);

        $user = User::where('phone', $request->phone)->get()->first();
        if ($user and $request->otp_code == $user->otp_code) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $user->otp_code = null;
            $user->temp_token = $token;
            $user->save();

            $response = ['data' => $user->phone, 'id' => $user->id, 'token' => $token, 'profile_completed' => ($user->age !== null), 'message' => 'success', 'status' => true];
            return response()->json($response, 200);
        } else {
            $response = ['data' => 'otp error', 'message' => 'fail', 'status' => false];
            return response()->json($response, 422);
        }

    }

    public function userImageChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }
        $image = $this->checkAndSaveImage($request,'users');
        $image_name = $image['image_name'];

        $user = User::find(auth('api')->user()->id);
        $user->image = $image_name;
        $user->save();
        $response = [ 'exif' => $user->exif, 'data' => 'https://liven-sa.com/public/storage/'.$user->image, 'message' => 'success', 'status' => true];

        return response()->json($response, 200);
    }

    public function getUsersAll()
    {
        if (auth('api')->user()->role == 'admin') {
            DB::statement(DB::raw('set @rownum=0'));
            $users = User::query()->with(
                array('dietitian' => function ($query) {
                    $query->select('id', 'name');
                })
            );
                if(request()->has('search') && request('search') != '' ){
                    $users = $users->where('name','like', '%'.request('search').'%')
                        ->orWhere('phone','like', '%'.request('search').'%')
                    ;
                }
                $users = $users->orderBy('id')->paginate(15,[DB::raw('@rownum  := @rownum  + 1 AS rownum'),'users.*']);
            $response = ['data' => $users, 'count' => $users->count(), 'message' => 'success', 'status' => true];
            return response()->json($response, 200);
        } else {
            $response = ['data' => 'no permissions', 'message' => 'fail', 'status' => false];
            return response()->json($response, 422);
        }

    }

    public function getPatientAll(Request $request)
    {
        if (auth('api')->user()->role == 'admin') {
            DB::statement(DB::raw('set @rownum=0'));
            $perPage = $request->per_page ?? 10;
                $users = User::query()->with(
                array('dietitian' => function ($query) {
                    $query->select('id', 'name');
                })
            );
            if($request->has('search') && $request->search != '' ){
                $users = $users->where('name','like', '%'.$request->search.'%')
                    ->orWhere('phone','like', '%'.$request->search.'%')
                ;
            }
            $users = $users->where('role', 'patient')->orderBy('id')->paginate($perPage,[DB::raw('@rownum  := @rownum  + 1 AS rownum'),'users.*']);
            $response = ['data' => $users, 'count' => $users->count(), 'message' => 'success', 'status' => true];
            return response()->json($response, 200);
        } else {
            $response = ['data' => 'no permissions', 'message' => 'fail', 'status' => false];
            return response()->json($response, 422);
        }

    }

    public function getPatientForDietitianAll( Request $request)
    {
        if (auth('api')->user()->role == 'dietitian') {
            DB::statement(DB::raw('set @rownum=0'));
            $perPage = $request->per_page ?? 10;
            $users = User::query()->with('dietitian:id,name');
            if($request->has('search') && $request->search != '' ){
                $users = $users->where('name','like', '%'.$request->search.'%')
                    ->orWhere('phone','like', '%'.$request->search.'%')
                ;
            }
            $users = $users->where('role', 'patient')
                ->where('dietitian_id', auth('api')->user()->id)
                ->orderBy('id')
                ->paginate($perPage,[DB::raw('@rownum  := @rownum  + 1 AS rownum'),'users.*']);
            $response = ['data' => $users, 'count' => $users->count(), 'message' => 'success', 'status' => true];
            return response()->json($response, 200);
        } else {
            $response = ['data' => 'no permissions', 'message' => 'fail', 'status' => false];
            return response()->json($response, 422);
        }

    }

    public function getDietitianAll()
    {
            DB::statement(DB::raw('set @rownum=0'));
            $users = User::query()->where('status', 'accepted');
            if(request()->has('search') && request('search') != '' ){
            $users = $users->where('name','like', '%'.request('search').'%')
                ->orWhere('phone','like', '%'.request('search').'%');}

            $users = $users->where('role', 'dietitian')->orderBy('id')->get([DB::raw('@rownum  := @rownum  + 1 AS rownum'),'users.*']);
			
			
            $response = ['data' => $users, 'count' => $users->count(), 'message' => 'success', 'status' => true];
            return response()->json($response, 200);


    }
    public function getTokenById($id)
    {
        $token = null;
        if ($id == 116) {
            $user = User::find($id);
            $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNGE1ZmMxMTk5Y2YzNmM4NDE3YTM3YjQzODZlM2Q3MmY3MjZmMmRiOTFlZjE1YmY0ZDAwNDdlZDg1NjQwMjY0ODUzYjRhMDVkYWMzYjkyN2MiLCJpYXQiOjE1ODcwNzAzMzksIm5iZiI6MTU4NzA3MDMzOSwiZXhwIjoxNjE4NjA2MzM5LCJzdWIiOiIxMTYiLCJzY29wZXMiOltdfQ.iR0Tzktkwvsa42zPg_R5UJzGXZvWCP7tcDSlaDVo00WiqiYCobrMh5fn76Pt0FrCJoYW_knEJiTfv3fodOoHTg';
        } else {
            $user = User::find($id);
            if($user){
                $token = $user->temp_token;
                $user->temp_token = null;
                $user->save();
            }

        }

        if ($token != null) {

            $diabetes = Diabetes::where('user_id', $id)->orderBy('date', 'desc')->orderBy('timing', 'desc')->limit(2)->get();

			$goal = Goal::where('user_id', $id)->first();

			if($goal != null) $goal = $goal->goal;
			
            $body = PatientBody::where('user_id', $id)->orderBy('date', 'desc')->limit(2)->get();

            $appointment = PatientReserve::with('appointment:id,from,to')
                ->where('patient_id', $id)
                ->where('reserved_date', '>=', Carbon::today()->format('Y-m-d'))
                ->orderBy('reserved_date', 'asc')->limit(1)
                ->select(['appointment_id', 'reserved_date'])->get();

            $advice = Advice::orderBy('created_at', 'desc')->first();

            $response = ['token' => $token, 'data' => [
                'user' => $user, 
				'diabetes' => $diabetes, 
				'goal' => $goal,
				'body' => $body, 
				'appointment' => $appointment, 
				'advice' => $advice
            ], 'message' => 'success', 'status' => true];
        } else {
            $response = ['error' => 'unauthenticated', 'status' => false];
            return response()->json($response, 422);
        }

        return response()->json($response, 200);


    }

    public function getUserProfile(Request $request)
    {
        $users = User::with('tickets', 'body', 'goals', 'countries', 'reports', 'meals', 'reports.category:id,name')
            ->with(array('dietitian' => function ($query) {
                    $query->select('id', 'name');
                }, 'meals' => function ($query) {
                    $query->select('id', 'user_id', 'date')->orderBy('date', 'desc')->limit(1);
                })
            )->where('id', request('user_id'))->get();
        $lastMeal = Meal::where('user_id', request('user_id'))->orderBy('date', 'desc')->limit(1)->first();

        if (isset($lastMeal))
            $lastMeal = explode(' ', $lastMeal->date)[0] . ' ' . $lastMeal->meals_today[count($lastMeal->meals_today) - 1]['timing'];
        else
            $lastMeal = '';

        $lastDiabetes = Diabetes::select('date', 'time_integer')->where('user_id', request('user_id'))->orderBy('date', 'desc')->orderBy('time_integer', 'desc')->limit(1)->first();
        if (isset($lastDiabetes))
            $lastDiabetes = explode(' ', $lastDiabetes->date)[0] . ' ' . $lastDiabetes->time;
        else
            $lastDiabetes = '';

        $lastBody = PatientBody::select('date', 'updated_at')->where('user_id', request('user_id'))->orderBy('date', 'desc')->limit(1)->first();
        if (isset($lastBody))
            $lastBody = $lastBody->updated_at;
        else
            $lastBody = '';


        $reserved = PatientReserve::with('appointment')
            ->where('patient_id',request('user_id'))
            ->where('reserved_date','>=',Carbon::today()->format('Y-m-d'))
            ->orderBy('reserved_date','asc')->limit(1)
            ->select(['appointment_id','reserved_date'])->get();
        $reservedArray = [];
        foreach ($reserved as $item) {
            array_push($reservedArray,[
                'title'=>$item->title,
                'notes'=>$item->notes,
                'reserved_date'=>$item->reserved_date,
                'concatenated'=>$item->appointment['concatenated'] ?? '',
                'concatenated_en'=>$item->appointment['concatenated_en'] ?? '',
            ]);
        }

		
		$meals = Meal::where('user_id', request('user_id'));
		if (isset($request['date']) && $request['date'] != 'null'){
			$meals = $meals->where('date', Carbon::createFromFormat('d-m-Y', request('date'))->add(3, 'hour')->format('Y-m-d'));
		}
		$meals = $meals->get('date');
		
	
		$activities = Activity::where('user_id', request('user_id'));
		if (isset($request['date'] ) && $request['date'] != 'null'){
			$activities = $activities->where('date',Carbon::createFromFormat('d-m-Y', request('date') )->add(3, 'hour')->add(3, 'hour')->format('Y-m-d'));
		}
		$activities = $activities->get('date');
		
		$_activities = [];
		foreach ($activities as $ac){
			$_activities[] = (object)['date' => date('d-m-Y', strtotime($ac->date))];
		}
		$_activities = collect($_activities);
		
        $medicine = PatientMedicine::where('user_id', request('user_id'));
		if (isset($request['date']) && $request['date'] != 'null'){
			$medicine = $medicine->where('date', Carbon::createFromFormat('d-m-Y', request('date'))->add(3, 'hour')->format('Y-m-d'));
		}
		$medicine = $medicine->get('date');
		
		$waters = Water::where('user_id', request('user_id'));
		if (isset($request['date']) && $request['date'] != 'null'){
			$waters = $waters->where('date', Carbon::createFromFormat('d-m-Y', request('date'))->add(3, 'hour')->format('Y-m-d'));
		}
		$waters = $waters->get('date');
		
		
		$all = $meals->union($_activities)->union($medicine)->union($waters);
		
		
		$all = $all->sortByDesc('date');
		
		$dates = [];
		
		foreach($all as $row){
			$dates[] = $row->date;
			// ->add(3, 'hour')
		}
		
	//	return response()->json($all, 200);
		
		//$all = $meals + $activities + $medicine + $waters;

        $response = ['data' => $users, 'more' => collect($dates)->unique()->values()->take(5), 'lastMeal' => $lastMeal,'coming_appointment'=>$reservedArray, 'lastDiabetes' => $lastDiabetes, 'lastBody' => $lastBody, 'count' => $users->count(), 'message' => 'success', 'status' => true];
		
        return response()->json($response, 200);

    }


    public function getProfile()
    {
        $response = ['data' => auth('api')->user(), 'message' => 'success', 'status' => true];
        return response()->json($response, 200);

    }

    public function changePasswordByPhone($request)
    {
        $user = User::where('phone', $request->phone)
            ->update(['password' => Hash::make($request->password)]);
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function changePasswordByUser(Request $request)
    {
		
//		return response()->json($request->user_id, 200);
	
	$user = null;
	
	if($request->user_id != null){
        $user = User::where('id', $request->user_id)
            ->update(['password' => Hash::make($request->password)]);
	}else{
	   $user = auth('api')->user()->update(['password' => Hash::make($request->password)]);
	}	
			
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function ChangeUserBlockUnblock(Request $request)
    {
        $user = User::find($request->user_id);
        $newStatus = ($user->is_active == 0) ? 1 : 0;
        $user->is_active = $newStatus;
        $user->save();
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function changePasswordByAuthId($request)
    {
        $user = User::find(auth('api')->user()->id);
        $user->password = Hash::make($request->password);
        $user->save();
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function ChangeUserCompletionRate(Request $request)
    {
        $user = User::find($request->user_id);
        $user->completion_rate = $request->completion_rate;
        $user->save();
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }


    public function setSubscriptionDates(Request $request)
    {
        $validator = Validator::make($request->all(), [
           // 'subscription_start_date' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }

        $user = User::find($request->user_id);
		
        //$subscription_end_date = Carbon::createFromFormat('m-d-Y', $request->subscription_start_date)->addMonths(1);
        //if (auth('api')->user()->role == 'admin') {
            
			if($request->has('subscription_end_date') && $request->subscription_end_date != '' && 
			$request->has('subscription_start_date') && $request->subscription_start_date != '') { 
              
				$user->subscription_end_date = Carbon::createFromFormat('m-d-Y', $request->subscription_end_date);
				$user->subscription_date = Carbon::createFromFormat('m-d-Y', $request->subscription_start_date);
				$user->subscription_state = 'paid';
				
            }else{
				$user->subscription_date = null;
				$user->subscription_state = 'free';
				$user->subscription_end_date = null;
			}
			
			$user->save();	
        //}

        
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function setPatientDietitian(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
          //  'dietitian_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }

        $user = User::find($request->patient_id);
        $user->dietitian_id = $request->dietitian_id;
        $user->save();
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    protected function setCompletionRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Arr::flatten($validator->errors()->all()), 'status' => false], 422);
        }

        $user = User::find($request->user_id);
        $user->completion_rate = $request->value;
        $user->save();
        $response = ['data' => $user, 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }

    public function createUserAsAdmin(Request $request)
    {
        if (auth('api')->user()->role == 'admin') {
            $user = new User();
            $user->phone = $request->phone;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->status = 'accepted';
            $user->role = 'admin';
            $user->save();
            $response = ['data' => $user, 'message' => 'success', 'status' => true];
            return response()->json($response, 200);
        }else{
            $response = ['data' => [], 'message' => 'no permissions', 'status' => false];
            return response()->json($response, 422);
        }
    }
    protected function checkAndSaveImage(Request $request, $pathName)
    {
        $image_name = '';
        $image_url = '';
        $exif = '';
		
        if ($request->image != '' && $request->image != null && $request->image != 'null') {
        
            $userAgent = Str::lower($request->header('User-Agent'));

            $image_name = Str::random(30);
            $image_name = $pathName.'/' . $image_name . '.jpg';
            $image_url = $image_name;

            $img = Image::make($request->file('image')->getRealPath());
		
			$img->orientate();
		
           // if (Str::contains($userAgent, 'iphone'))
           //     $img->rotate(-90);

            $img->save(public_path('storage/' . $image_name));

        }

        return ['image_name'=>$image_name,'image_url'=>$image_url, 'exif' => $exif ];
    }

    public function uploadEducationalMaterialFile(Request $request)
    {
        if ($request->hasFile('file')) {
            move_uploaded_file($_FILES['file'], public_path('storage/' . $request->get('week_id') . '.pdf'));
        }
        
        $response = ['data' => [], 'message' => 'success', 'status' => true];
        return response()->json($response, 200);
    }
}