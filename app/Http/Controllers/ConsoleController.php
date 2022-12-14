<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Ramsey\Uuid\Uuid;

class ConsoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $used_token = auth()->user()->currentAccessToken();
            $last_seen = $used_token->last_used_at;
            $used_uuid = $used_token->tokenable_id;
            Admin::where('uuid', $used_uuid)->update(['last_seen' => $last_seen]);
            return $next($request);
        });
    }

    public function admin()
    {
        $admins = Admin::orderBy('created_at')->get();
        foreach ($admins as $admin) {
            $admin->first_name = ucwords($admin->first_name);
            $admin->last_name = ucwords($admin->last_name);
            if ($admin->last_seen) {
                $admin->last_active = Carbon::parse($admin->last_seen)->diffForHumans();
            } else {
                $admin->last_active = 'No Activity';
            }
            $admin->block_loading = false;
            $admin->delete_loading = false;
        }

        return response()->json($admins, 200);
    }

    public function admin_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:16|unique:admin,username',
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

    public function admin_reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $id = $request->uuid;
        $new_password = Str::random(8);

        $query = Admin::where('uuid', $id);

        $username = $query->first()->username;

        $query->update([
            'password' => Hash::make($new_password),
        ]);

        return response()->json(['username' => $username, 'new_password' => $new_password], 200);
    }

    public function admin_block(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $id = $request->uuid;
        $query = Admin::where('uuid', $id);
        $admin = $query->first();
        if ($admin->is_active == 1) {
            $query->update(['is_active' => 0]);

            return response()->json(['message' => 'Admin has been blocked.'], 200);
        } else {
            $query->update(['is_active' => 1]);

            return response()->json(['message' => 'Admin has been unblocked.'], 200);
        }
    }

    public function admin_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $id = $request->uuid;
        Admin::where('uuid', $id)->delete();

        return response()->json(['message' => 'Admin has been deleted.'], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->each(function ($token) {
            $token->delete();
        });

        if (!auth('sanctum')->check()) {
            return [
                'message' => 'You have successfully logged out and the token was successfully deleted'
            ];
        } else {
            return [
                'message' => 'You are still logged in'
            ];
        }
    }

    public function profile_update(Request $request)
    {
        $used_token = auth()->user()->currentAccessToken();
        $used_uuid = $used_token->tokenable_id;

        Admin::where('uuid', $used_uuid)->update([
            'username' => 'xFa173JNsa',
        ]);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|alpha|max:32',
            'last_name' => 'required|string|alpha|max:32',
            'username' => 'required|string|alpha_num|max:16|unique:admin,username',
            'email' => 'required|email',
            'phone' => ['required', 'min:10', 'max:15', "regex:/^(([\+]?[6]{1}[2]{1})|0)[0-9]{9,12}$/"],
            'gender' => 'required',
            'birthday' => 'required|date|before:-17 years',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Admin::where('uuid', $used_uuid)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'hp' => $request->phone,
            'gender' => $request->gender,
            'birthday' => date('Y-m-d', strtotime($request->birthday)),
        ]);

        return response()->json(['message' => 'Profile has been updated.'], 200);
    }
    public function get_profile()
    {
        $used_token = auth()->user()->currentAccessToken();
        $used_uuid = $used_token->tokenable_id;

        $admin = Admin::where('uuid', $used_uuid)->first();
        return response()->json($admin, 200);
    }
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|alpha_num|max:16',
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $used_token = auth()->user()->currentAccessToken();
        $used_uuid = $used_token->tokenable_id;
        $old_password = $request->old_password;
        $new_password = $request->new_password;

        $query = Admin::where('uuid', $used_uuid);
        $check = $query->first();

        if ($request->username != $check->username){
            return response()->json(['message' => 'Wrong username and old password.'], 422);
        }

        if (!Hash::check($old_password,$check->password)){
            return response()->json(['message' => 'Wrong username and old password.'], 422);
        }

        $query->update([
            'password' => Hash::make($new_password),
        ]);

        return response()->json(['message' => 'Password has been updated.'], 200);
    }
}
