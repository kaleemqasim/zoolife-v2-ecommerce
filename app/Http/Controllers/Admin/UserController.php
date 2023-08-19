<?php

namespace App\Http\Controllers\Admin;

use DB;
use Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function get_user_profile(Request $request)
    {

        $dr['status'] = 100;
        $dr['error'] = true;
        // $dr['data'] = [];
        $dr['message'] = 'Unable to process the request. Please try later.';
        $user_id = $request->user_id;
        $res = User::where('id', '=', $user_id)->where('verify', '=', 1)->first();
        // $res = DB::table('users')->select('*')->where('id', '=', $user_id)->where('verify', '=', 1)->get();
        if ($res) {

            $dr['status'] = 200;
            $dr['data'] = $res;
            $dr['error'] = false;
            $dr['message'] = 'User Profile data.';
        }
        return Response::json($dr);
    }
    public function update_user_profile(Request $request)
    {
        $dr['status'] = 100;
        $dr['error'] = true;
        $dr['data'] = [];
        $dr['message'] = 'Unable to update Profile. Please check your username and try again.';
        $user_id = $request->user_id;
        $blance = array(
            'username' => $request->username,
            'city_id' => $request->city_id,
            'gender' => $request->gender
        );
        $res = User::where('id', '=', $user_id)->where('verify', '=', 1)->first();
        if ($res) {
            // $id = $res[0]->id;
            $res->update($blance);
            // $user = User::where('id', '=', $id)->first();
            $dr['status'] = 200;
            $dr['data'] = $res;
            $dr['error'] = false;
            $dr['message'] = 'Your Profile has been updated.';
        }
        return Response::json($dr);
    }

    public function update_user_device_token(Request $request)
    {
        $dr['status'] = 100;
        $dr['error'] = true;
        $dr['data'] = [];
        $dr['message'] = 'Unable to update Profile. Please check your username and try again.';
        $user_id = $request->user_id;
        $blance = array(
            'device_token' => $request->device_token,
            'device_type' => $request->device_type
        );
        $res = User::where('id', '=', $user_id)->where('verify', '=', 1)->first();
        // print_r($res);
        if ($res) {
            // $id = $res[0]->id;
            $res->update($blance);
            // $user = DB::table('users')->select('*')->where('id', '=', $id)->get();
            $dr['status'] = 200;
            $dr['error'] = false;
            $dr['message'] = 'Your Device has been updated.';
            $dr['data'] = $res;
        }
        return Response::json($dr);
    }
}
