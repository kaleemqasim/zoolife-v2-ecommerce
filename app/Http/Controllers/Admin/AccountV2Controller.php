<?php



namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use Validator;
use Image;
use Response;
use App\Models\User;
use App\Helpers\SmsHelper;
use DB;
use App\Traits\ApiResponseTrait;
use App\Traits\CommonHelperTrait;

class AccountV2Controller extends Controller
{
    use ApiResponseTrait;
    use CommonHelperTrait;
    
    public function index(Request $request)
    {
        $accounts = User::get();
        $page_title = 'Manage accounts';

        return view('admin/account/index', compact('page_title', 'accounts'));
    }

    public function create(Request $request)
    {
        $page_title = 'Add accounts';
        return view('admin/account/add', compact('page_title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'username' => 'required',
            'contact_no' => 'required',
            'password' => 'min:6',
            'confirm_password' => 'required_with:password|same:password|min:6'
        ]);

        $password = Hash::make($request->password);
        $account = new User;
        $account->email = $request->email;
        $account->username = $request->username;
        $account->phone = $request->contact_no;
        $account->password = $password;
        if ($request->get('status', 0) == 1) {
            $account->status = 'Yes';
        } else {
            $account->status = 'No';
        }
        $account->save();
        return redirect()->route('admin.account.show')->with('success', 'saved');
    }

    public function show($id)
    {
        $account = User::where('id', '=', $id)->first();
        return view('admin/account/edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = User::find($id);
        $account->email = $request->email;
        $account->username = $request->username;
        $account->phone = $request->contact_no;
        if ($request->get('status', 0) == 1) {
            $account->status = 'Yes';
            $account->verify = '1';
        } else {
            $account->status = 'No';
            $account->verify = '0';
        }

        if (empty($request->password)) {
            $account->password = $request->oldpassword;
        } else {

            $password = Hash::make($request->password);
            $account->password = $password;
        }

        $account->save();
        return redirect()->route('admin.account.show')->with('success', 'saved');
    }

    public function destroy($id)
    {
        $p = User::find($id);
        $p->delete(); //delete the client
        return redirect()->route('admin.account.show')->with('success', 'saved');
    }

    public function updatedescliamer(Request $request)
    {
        $id = $request->user_id;
        $account = User::find($id);
        if($account){

            $account->disclaimer = '1';
            $data = $account->save();
            if ($data) {
                $dr['error'] = false;
                $dr['status'] = '200';
                // $dr['message']='Report Added successfully';
                //   $dr['message'] = 'تم التبيلغ عن الاعلان';
                $dr['message'] = trans('messages.report_added');
            }
        } else {
            $dr['error'] = true;
            $dr['status'] = '100';
            // $dr['message']='Report Added successfully';
            //   $dr['message'] = 'تم التبيلغ عن الاعلان';
            $dr['message'] = trans('messages.no_data');
        }

        return Response::json($dr);

    }

    public function registerapi(Request $request)
    {
        $dr['status'] = 100;
        $dr['error'] = true;
        // $dr['message'] = 'unable to process this request. Please try again later.';
        $dr['message'] = trans('messages.unable_to_process_request');
        // $dr['message'] = 'يرجى المحاولة لاحقا';
        $user_name = $request->username;
        // $email = $request->email;
        $phone = $request->phone;
        $city_id	 = $request->city_id;
        if($city_id=='') {
            return $this->errorResponse(trans('messages.request_failed'), [], 400);
        }
        $gender	 = $request->gender;
        $phone_country_code	 = $request->phone_country_code;
        $phone=$phone_country_code.$phone;
        $password = $request->password;
        $passw_hash = md5($password);

        $usernames = DB::table('users')->select('login')->get();
        $digits=6;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        // $otp = rand(10, 1000000);

        $check = User::where('username', $user_name)->first();
        if($check)
        {
            return $this->errorResponse(trans('messages.username_exists'), [], 400);
        }

        $check = User::where('phone', $phone)->first();
        if($check)
        {
            return $this->errorResponse(trans('messages.phone_exists'), [], 400);
        }

        $blance = array(
            'username' => $user_name,
            'password' => $passw_hash,
            'phone' => $phone,
            'verify' => 1,
            'otp' => $otp,
            'phone_country_code'=>$phone_country_code,
            'city_id'=>$city_id,
            'gender'=>$gender
        );
        $inserted = User::create($blance);

        if (!empty($inserted->id)) {
            $message = trans('messages.verify_account').' ' . $otp .' '. trans('messages.here_is_otp_code');
            // $smsRes = SmsHelper::sendSMS($phone, $message);
            $smsRes = $this->sendWhatsappMessage($phone, $message);

            return $this->successResponse(trans('messages.account_created_check_phone') . $otp, [], 200);
        } else {
            return $this->errorResponse(trans('messages.request_failed'), [], 400);
        }

    }

    public function verify_otp(Request $request)
    {
        $dr['status'] = 100;
        $dr['error'] = true;
        // $dr['message'] = 'عفوا....اسم المستخدم/رقم الهاتف  غير مسجل لدينا';
        $dr['message'] = trans('messages.unable_to_send_otp');

        $otp = $request->otp;
        $phone = $request->phone;
        $phone_country_code = $request->phone_country_code;
        $phone=  $phone_country_code.$phone;
        $res = User::where('phone', $phone)->where('otp', $otp)->first();
        // print_r($res);
        // die();
        if ($res) {
            $id = $res->id;

            $blance = array(
                'verify' => 1,
                'otp' => null,
            );
            $res->update($blance);
            return $this->successResponse(trans('messages.account_verified'), $res, 200);

        } else {
            return $this->errorResponse(trans('messages.invalid_Username/Email/Phone'), [], 400);
        }
        return Response::json($dr);
    }
    public function resend_otp(Request $request)
    {
        $dr['status'] = 100;
        $dr['error'] = true;
        $dr['message'] = trans('messages.unable_to_send_otp');

        $digits=6;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $user_id = $request->phone;
        $phone_country_code = $request->input('phone_country_code');
        $user_id=  $phone_country_code.$user_id;
        $res = User::where('phone', 'LIKE', '%'.$user_id)->first();
        if ($res) {
            $blance = array(
                'otp' => $otp
            );
            $data = $res->update($blance);
            $phone = $res->phone;
            $message = trans('messages.verify_account').' ' . $otp .' '. trans('messages.here_is_otp_code');
            // $smsRes = SmsHelper::sendSMS($phone, $message);
            $smsRes = $this->sendWhatsappMessage($phone, $message);
           
            return $this->successResponse(trans('messages.otp_resent') . $otp, [], 200);

        }
        return Response::json($dr);
    }
    public function loginapi(Request $request)
    {
        $dr['status'] = 100;
        $dr['error'] = true;
        $dr['message'] = 'Your Email/Phone is invalid';

        $phone = $request->phone;
        $password = $request->password;
        $phone_country_code= $request->phone_country_code;
        $phone=  $phone_country_code.$phone;
        $userCheck = User::where('phone', $phone)->first();
        
        
        if ($userCheck) {
            $phone_country_codehas =$userCheck->phone_country_code;
            if ($userCheck->verify != 1) {
                $dr['error'] = true;
                $dr['message'] = trans('messages.account_not_active');
            } else {
                $digits=6;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
                $userCheck->otp = $otp;
                $userCheck->save();
                $message = trans('messages.verify_account').' ' . $otp .' '. trans('messages.here_is_otp_code');
                // $smsRes = SmsHelper::sendSMS($phone, $message);
                
                         if($phone_country_codehas==''){
             $blance = array(
                'phone_country_code' => $phone_country_code,
         
            );
            $userCheck->update($blance);
            
        }
        
        $smsRes = $this->sendWhatsappMessage($phone, $message);
                try{
           
                    return $this->successResponse(trans('messages.verify_account') .' '.$otp, [], 200);
                }catch(\Exception $e) {
                    info('some error occured in sending sms');
                    info($e->getMessage());
                    $dr['error'] = true;
                    $dr['data'] = [];
                    $dr['message'] = trans('messages.no_data');
                    return $this->errorResponse(trans('messages.request_failed'), [], 400);
                }
            }
        } else {
            return $this->errorResponse(trans('messages.phone_incorrect'), [], 400);

        }
    }

    public function reset_password(Request $request)
    {
        $dr['message'] = trans('messages.unable_to_process_request');

        $phone = $request->phone;
        $res = User::where('phone', $phone)->first();

        if ($res) {
            // $id = $res[0]->id;
            // $otp = rand(10, 1000000);
            $digits=6;
            $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

            $blance = array(
                'otp' => $otp,
            );
            $res->update($blance);
            $phone = $res->phone;
            $message = trans('messages.verify_account').' ' . $otp .' '. trans('messages.here_is_otp_code');
            $smsRes = SmsHelper::sendSMS($phone, $message);
            $dr['smsRes'] = $smsRes;
            
            return $this->successResponse(trans('messages.veirfy_otp') .' '. $otp, [], 200);
        }
        
        return $this->errorResponse(trans('messages.no_data'), [], 400);

    }

    public function update_password(Request $request)
    {
        $dr['message'] = trans('messages.password_update_failed');

        $phone = $request->phone;
        $password = $request->password;
        $res = User::where('phone', '=', $phone)->where('verify', '1')->first();
        if ($res) {
            $id = $res->id;
            $pin =  rand(10, 1000000);
            $hash = md5($password);
            $blance = array(
                'password' => $hash,
            );
            $res->update($blance);
            
            return $this->successResponse(trans('messages.password_updated'), [], 200);

        }
        return $this->successResponse(trans('messages.password_update_failed'), [], 200);

    }
}