<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_add(Request $request)
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

    public function admin_update()
    {
    }

    public function admin_block(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $id = $request->uuid;
        $query = Admin::where('uuid',$id);
        $admin = $query->first();
        if ($admin->is_active == 1){
            $query->update(['is_active' => 0]);

            return response()->json(['message'=>'Admin has been blocked.'],200);
        } else {
            $query->update(['is_active' => 1]);

            return response()->json(['message'=>'Admin has been unblocked.'],200);
        }
    }

    public function admin()
    {
        $admins = Admin::orderBy('created_at')->get();
        foreach ($admins as $admin) {
            $admin->first_name = ucwords($admin->first_name);
            $admin->last_name = ucwords($admin->last_name);
            if($admin->last_seen){
                $admin->last_active = Carbon::parse($admin->last_seen)->diffForHumans();
            } else {
                $admin->last_active = 'No Activity';
            }
            $admin->block_loading = false;
            $admin->delete_loading = false;
        }

        return response()->json($admins, 200);
    }

    public function admin_delete()
    {
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
}
