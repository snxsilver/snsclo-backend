<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
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

        $admin = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['status' => 200, 'data' => $admin]);
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        if (!Auth::guard('user-login')->attempt($request->only('username', 'password'))) {
            return response()->json(['status' => 401, 'message' => 'Unauthorized']);
        }

        $admin = User::where('username', $request['username'])->firstOrFail();
        $token = $admin->createToken('api_token')->plainTextToken;
        $admin->token = $token;
        $admin->token_type = 'Bearer';

        return response()->json([
            'status' => 200,
            'data' => $admin
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
