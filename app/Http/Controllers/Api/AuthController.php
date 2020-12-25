<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'fcm_token' => 'required'
        ]);

        if ($validator->fails()) {
            $response = ['data' => $validator->errors(), 'status' => false];
            return response()->json($response,422);
        }
        
        $user = User::where('name', $request->username)->firstOrFail();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {

                $fcm_token = $request->fcm_token ?? null;
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $user->fcm_token = $fcm_token;
                $user->save();

                $response = ['token' => $token, 'id' => $user->id, 'status' => true];

                return response()->json($response, 200);
            } else {
                $response = ['message' => 'Password missmatch', 'status' => false];
                return response()->json($response, 422);
            }

        } else {
            $response = ['message' => 'User does not exist', 'status' => false];
            return response()->json($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $response = ['message' => 'You have been succesfully logged out!', 'status' => true];
        
        return response()->json($response, 200);
    }

    public function not_authenticated(Request $request)
    {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}