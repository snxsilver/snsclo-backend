<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class AdminController extends Controller
{
    public function username()
    {
        return 'username';
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $admin = Admin::create([
            'uuid' => Uuid::uuid4()->getHex(),
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['status' => 200, 'data' => $admin], 200);
    }

    public function login(Request $request)
    {
        if (!Auth::guard('admin-login')->attempt($request->only('username', 'password'))) {
            return response()->json(['status' => 401, 'message' => 'Wrong username and password'], 401);
        }

        $admin = Admin::where('username', $request['username'])->firstOrFail();
        $token = $admin->createToken('admin_token')->plainTextToken;
        $admin->token = $token;
        $admin->token_type = 'Bearer';

        return response()->json([
            'status' => 200,
            'data' => $admin
        ], 200);
    }

    public function getUser(){
        if(!Auth::guard('admin')->check()){
            return response()->json(['status' => 401, 'message' => 'Unauthorized'], 401);
        } else {
            $admin = auth('sanctum')->user();
            return response()->json(['status' => 200, 'message' => $admin], 200);
        }
    }

    public function logout()
    {
        Auth::logout();

        if(!Auth::check()){
            return [
                'message' => 'You have successfully logged out and the token was successfully deleted'
            ];
        } else {
            return [
                'message' => 'You are logged in'
            ];
        }
    }
}
