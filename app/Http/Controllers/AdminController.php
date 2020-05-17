<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminController extends Controller
{
    public function getUnapprovedUsers() {
        $users = User::where('approved', false)->get();
        return response()->json(['users' => $users], 200);
    }

    public function approveUser(Request $request) {
        $user = User::where('id', $request->id)->update([
            'approved' => true,
            'virtual_card' => 1000
        ]);
        if($user) {
            $user = User::where('id', $request->id)->get();
            return response()->json(['message' => 'Approved and virtual credit added', 'user' => $user], 400);
        }
        return response()->json(['error' => 'Error: Try again'], 400);
    }
}
