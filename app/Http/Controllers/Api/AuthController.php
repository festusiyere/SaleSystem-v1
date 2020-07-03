<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api')->only('userLogout');

    }

    public function register(Request $request){

        if (!empty($request->all())) {

            $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        Profile::create([
            'user_id' => $user->id,
        ]);

        if(auth()->attempt($request->only('email', 'password'))){
            // return "Not Logged in";
            $access = auth()->user()->createToken('authToken')->accessToken;

            return response()->json([
                'user' => $user,
                'accessToken' => $access,
            ], 200);

        }


        } else {

            return response()->json([
                'message' => 'Empty content'
        ], 404);

        }

    }

    public function login(Request $request){

        $loginData = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if (!auth()->attempt($loginData)) {
                return response()->json([
                'message' => 'Invalid credentials',
            ], 401);

            }

            $access = auth()->user()->createToken('authToken')->accessToken;

            return response()->json([
                'user' => auth()->user(),
                'accessToken' => $access,
        ], 200);

    }

    public function userLogout(Request $request){

        auth()->user()->token()->revoke();
        return response()->json('Logout Successful', 200);

    }

    public function user(Request $request){
        return response()->json(auth()->user(), 200);
    }

    public function emailCheck(Request $request){

        $user = User::where('email', $request['email'])->exists();
        if($user) {
            return 1;
        }
        return 0;

    }


}
