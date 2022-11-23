<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
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
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => 200, 'data' => $admin, 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        if (!Auth::guard('admin')->attempt($request->only('username', 'password'))) {
            return response()->json(['status' => 401, 'message' => 'Unauthorized']);
        }

        $admin = Admin::where('username', $request['username'])->firstOrFail();
        $token = $admin->createToken('auth_token')->plainTextToken;
        $admin->token = $token;
        $admin->token_type = 'Bearer';

        return response()->json([
            'status' => 200,
            'data' => $admin
        ]);
    }
}
