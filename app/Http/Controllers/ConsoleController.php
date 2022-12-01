<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class ConsoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['message' => 'Success']);
    }

    public function bodrex()
    {
        return response()->json(['message' => 'Success']);
    }

    public function logout()
    {
        $used_token = auth()->user()->currentAccessToken();
        $last_seen = $used_token->last_used_at;
        $uuid = $used_token->tokenable_id;
        Admin::where('uuid',$uuid)->update(['last_seen' => $last_seen]);

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
