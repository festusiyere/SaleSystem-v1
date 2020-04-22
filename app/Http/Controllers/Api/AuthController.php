<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            // Profile::create(compact($user->id));

            $access = $user->createToken('authToken')->accessToken;

            return response()->json([
                'user' => $user,
                'accessToken' => $access,
            ]);

        } else {

            return response()->json([
                'message' => 'Empty content'
            ]);
        }

    }

    public function login(Request $request){

        $loginData = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if (!auth()->attempt($loginData)) {
                return response()->json([
                'message' => 'invalid credentials',
            ]);

            }

            $access = auth()->user()->createToken('authToken')->accessToken;

            return response()->json([
                'user' => auth()->user(),
                'accessToke' => $access,
            ]);

    }

    public function userLogout(Request $request){

        auth()->user()->token()->revoke();
        return response()->json('Logout Successful', 200);

    }

}
