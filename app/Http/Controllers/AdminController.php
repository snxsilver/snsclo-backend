<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Config;
use Laravel\Sanctum\PersonalAccessToken;

class AdminController extends Controller
{
    public function username()
    {
        return 'username';
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

        Auth::guard('admin-login')->logout();

        if(Auth::guard('admin-login')->check()){
            return response()->json([
                'status' => 401,
                'data' => 'Unable to get data'
            ], 401);            
        }

        return response()->json([
            'status' => 200,
            'data' => $admin
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $admin = Admin::create([
            'uuid' => Uuid::uuid4()->getHex(),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => 1,
        ]);

        return response()->json(['status' => 200, 'data' => $admin], 200);
    }

    public function register_super_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $cek = Admin::where('super_admin', 1)->first();

        if ($cek) {
            return response()->json(['status' => 401, 'message' => 'Super admin is already registered'], 401);
        }

        $admin = Admin::create([
            'uuid' => Uuid::uuid4()->getHex(),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'super_admin' => 1,
            'is_active' => 1,
            'role' => 'supervisor',
            'last_seen' => now()
        ]);

        return response()->json(['status' => 200, 'data' => $admin], 200);
    }

    public function reset_super_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nama_sd' => 'required',
            'nama_smp' => 'required',
            'nama_sma' => 'required',
            'nama_jurusan' => 'required',
            'nama_ukm' => 'required',
            'nama_bimbel' => 'required',
            'nama_yayasan' => 'required',
            'nama_startup' => 'required',
            'base_pin' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if ($request->nama_lengkap !== config('reset_super_admin.nama_lengkap')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_sd !== config('reset_super_admin.nama_sd')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_smp !== config('reset_super_admin.nama_smp')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_sma !== config('reset_super_admin.nama_sma')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_jurusan !== config('reset_super_admin.nama_jurusan')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_ukm !== config('reset_super_admin.nama_ukm')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_bimbel !== config('reset_super_admin.nama_bimbel')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_yayasan !== config('reset_super_admin.nama_yayasan')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->nama_startup !== config('reset_super_admin.nama_startup')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->username !== config('reset_super_admin.username')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
        if ($request->base_pin !== config('reset_super_admin.base_pin')) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }

        $admin = Admin::where('username', config('reset_super_admin.username'))
            ->where('super_admin', config('reset_super_admin.super_admin'))
            ->update(['password' => Hash::make($request->password)]);

        if ($admin) {
            return response()->json(['message' => 'Super admin has been reset'], 200);
        } else {
            return response()->json([
                'message' => 'Unable to reset super admin'
            ], 401);
        }
    }
}
