<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userLogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'code'    => 402,
                'message' => "Validation error",
                'errors'  => $validator->errors()->toArray(),
                'result'  => [],
            ];
            return response()->json($response);
        }

        $credentials = $request->only('phone', 'password');

        $customer = User::where(['phone' => $request->phone])->first();

        if (!empty($customer) && $customer->id) {
            $passHash = md5($request->password);
            if ($customer->password == $passHash) {
            // if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
                // Auth::login($customer);
                Auth::loginUsingId($customer->id);
            // if (Hash::check($request->input('password'), $customer->password)) {
                // if ($customer->status == 1) {
                    $response = [
                        'code'    => 200,
                        'message' => "Login Successfully",
                        'result'  => $customer,
                    ];
                // } else {
                //     $response = [
                //         'code'    => 422,
                //         'message' => "Your account is inactive please contact to admin.",
                //         'result'  => [],
                //     ];
                // }
            } else {
                $response = [
                    'code'    => 422,
                    'message' => "Password is incorrect.",
                    'result'  => [],
                ];
            }
        } else {
            $response = [
                'code'    => 422,
                'message' => "User not found.",
                'result'  => [],
            ];
        }

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }

    public function myPosts(Request $request)
    {
        $user = \Auth::user();
        if (!empty($user->id)) {
            $data = [];
            $posts = Item::select('items.*', 'u.name as author')
                            ->leftjoin('users as u','u.id','fromUserId')
                            ->where('post_type', "normal")
                            ->where('fromUserId', $user->id)
                            ->orderBy('updated_at', 'DESC');
            $data['posts'] = $posts->paginate(5);

            $auction = Item::select('items.*', 'u.name as author')
                            ->leftjoin('users as u','u.id','fromUserId')
                            ->where('post_type', 'auction')
                            ->where('fromUserId', $user->id)
                            ->orderBy('updated_at', 'DESC');
            $data['auction'] = $auction->paginate(5);
            return view('site.my-post-list', compact('data'));
        } else {
            abort(404);
        }
    }
}
