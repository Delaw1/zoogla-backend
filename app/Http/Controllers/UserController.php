<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

class UserController extends Controller
{
    public function user(Request $request) {
        return response()->json($request->user());
    }
} 
