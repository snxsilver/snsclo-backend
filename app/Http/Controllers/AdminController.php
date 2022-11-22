<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $admin = Admin::create([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => 200, 'data' => $admin, 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function login(Request $request){
        // $admin = [
        //     'username' => $request->username,
        //     'password' => $request->password
        // ];
        if(Auth::guard('admin')->attempt($request->all())){
            return response()->json(['status' => 200, 'message' => 'Success']);
        }
    }
}
