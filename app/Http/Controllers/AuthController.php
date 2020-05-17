<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|unique:users',
            'phone_number' => 'required|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success = $user;
        $success['token'] = $user->createToken('zoogla')->accessToken;
        return response()->json(['user' => $success], 201);
    }

    public function login(Request $request) {
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        } else {
            Auth::attempt(['username' => $request->username, 'password' => $request->password]);
        }
        if(Auth::check()) {
            $user = Auth::user();
            if($user->approved == false) {
                return response()->json(['message' => 'Account not yet approved'], 400);
            }
            $success = $user;
            $success['token'] = $user->createToken('myApp')->accessToken;
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Invalid Credentials'], 400);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'User successfully logout']);
    }
}
