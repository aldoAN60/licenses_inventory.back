<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class userController extends Controller
{
    public function index(){
        $users = User::all();
        
        return response()->json($users);
    }
    public function getAuthUser(Request $request){
        $username = $request->username;
        $employee_number = $request->password;
        $user = User::where('employee_number',$employee_number)
        ->where('employee_name',$username)
        ->first();
        if ($user) {
            return response()->json(['authenticated' => true]);
        }else{
            return response()->json(['authenticated' => false]);
        }

    }

    
}