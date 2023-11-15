<?php

namespace App\Http\Controllers;

use App\Models\adminUser;
use Illuminate\Http\Request;

class adminUserController extends Controller
{
    public function getAuthUser(Request $request){

        $admin_name = $request->username;
        $admin_password = $request->password;
        
        $user = adminUser::where('admin_name',$admin_name)
        ->where('admin_password',$admin_password)
        ->first();
        if ($user) {
            return response()->json(['authenticated' => true]);
        }else{
            return response()->json(['authenticated' => false]);
        }

    }
}
