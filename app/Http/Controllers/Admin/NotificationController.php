<?php



namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;
use App\ZoolifeNotification;
use Validator;
use File;
use Response;
use DB;



class NotificationController extends Controller

{

    public function api_notification(Request $request)
    {
        $sender_user_id = $request['sender_user_id'];
        $ads_id = $request['ads_id'];
        $content_of_sender = $request['content'];


        $notifi = DB::table('items')->select()->where('id', '=', $ads_id)->get();
        $ads_title = $notifi[0]->itemTitle;
        $fromUserId = $notifi[0]->fromUserId;
        $userid = $notifi[0]->fromUserId;
        $user = DB::table('users')->select()->where('id', '=', $userid)->get();

        $ads_userdetail = $user[0]->fullname;
        $device_token = $user[0]->device_token;



        $user = DB::table('users')->select()->where('id', '=', $sender_user_id)->get();

        $userdetail = $user[0]->fullname;
        $msg = "$userdetail Posted comment on your Post $ads_title";
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        $token = $device_token;
        $title = "Commented";
        $body = $msg;
        $notification = array('title' => $title, 'body' => $body, 'badge' => 1);
        $arrayToSend = array('to' => $token, 'notification' => $notification, 'data' => $notification);
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAarKgHyI:APA91bEucI6EaFo6UlFyLM7YMqdS1xiXVZYlX7ReH7wDQDUHhDx7NCmcnBu7ti3fDTzrk0TjfQ1Zo0wQyk_fgGBd3DOJ7CFM8VqanY1j6nNAXiFMzjvtjhAH3LWavPAovNMXY9c6z5X7';
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $content  = curl_exec($ch);
        curl_close($ch);



        if ($content) {

            $blance = array(
                'user_id' => $fromUserId,
                'ads_id' => $ads_id,
                'sender_id' => $sender_user_id,
                'content' => $content_of_sender,
                'updated_at' => date('Y/m/d H:i:s'),
            );
            $inserted = DB::table('zoolife_notification')->insert($blance);
            if ($inserted) {
                $dr['error'] = false;
                $dr['status'] = '200';
                $dr['message'] = 'Notification Added Successfully';
                // $dr['data']=$data;
                return Response::json($dr);
            }
        }
    }

    public function add_msg_badge(Request $request)
    {
        $userId = $request['user_id'];
        $notifi  = DB::table('users')->select()->where('id', '=', $userId)->get();
        if($notifi->isNotEmpty()){
        $getting_msg_count = $notifi[0]->msg_badge + 1;
        $blance = array(
            'msg_badge' => $getting_msg_count
        );
        $updated = DB::table('users')->where('id', '=', $userId)->update($blance);
        if ($updated) {
            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['message'] = 'Add message successful';
            return Response::json($dr);
        }
        }else{
            $dr['error'] = true;
            $dr['status'] = '101';
            $dr['message'] = 'No user exist with this Id';
            return Response::json($dr);
        }
    }
    public function read_msg_badge(Request $request)
    {
        $userId = $request['user_id'];
        $read_msg = $request['read_msg'];
        $notifi  = DB::table('users')->select()->where('id', '=', $userId)->get();
        // print_r($notifi);
        // die();
        if($notifi->isNotEmpty()){
        $getting_msg_count = $notifi[0]->msg_badge - $read_msg;
        $blance = array(
            'msg_badge' => $getting_msg_count
        );
        $updated = DB::table('users')->where('id', '=', $userId)->update($blance);
        if ($updated) {
            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['message'] = 'clear message successful';
            return Response::json($dr);
        }
        }else{
            $dr['error'] = true;
            $dr['status'] = '101';
            $dr['message'] = 'No user exist with this Id';
            return Response::json($dr);
        }

    }

    public function get_all_noti_by_userid(Request $request)
    {
        $userId = $request['user_id'];

        $notifi  = ZoolifeNotification::where('user_id', '=', $userId)->get();
        $i = 0;
        foreach ($notifi as $notifi) {
            $userid = $notifi->user_id;
            $data[$i]['user_id'] = $notifi->user_id;
            $data[$i]['ads_id'] = $notifi->ads_id;
            $data[$i]['sender_id'] = $notifi->sender_id;
            $data[$i]['content'] = $notifi->content;
            $data[$i]['isread'] = $notifi->isread;
            $data[$i]['created_at'] = $notifi->created_at;
            $data[$i]['updated_at'] = $notifi->updated_at;
            $i++;
        }

        if (!empty($data)) {
            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['message'] = 'success';
            $dr['data'] = $data;
            $blance = array(
                'noti_badge' => '0'
            );
            $updated = DB::table('users')->where('id', '=', $userId)->update($blance);
            if ($updated) {

                $req = array(
                    "isread" => '0'
                );
                $notify = DB::table('zoolife_notification')->where('user_id', '=', $userId)->update($req);
            }
            return Response::json($dr);
        } else {
            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['data'] = [];
            $dr['message'] = 'error';
        }
        return Response::json($dr);
    }
    
    function markNotificationRead(Request $request) {
        $notificationId = $request->notification_id;
        $notifi  = DB::table('zoolife_notification')->select()->where('id', '=', $notificationId)->first();
        if($notifi){
            $updated = DB::table('zoolife_notification')->where('id', '=', $notificationId)->update(['isread' => 1]);
            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['message'] = 'clear message successful';
            return Response::json($dr);
        }else{
            $dr['error'] = true;
            $dr['status'] = '101';
            $dr['message'] = 'No notification exist with this Id';
            return Response::json($dr);
        }
    }
    
    function deleteNotification(Request $request) {
        $notificationId = $request->notification_id;
        $notifi  = DB::table('zoolife_notification')->select()->where('id', '=', $notificationId)->first();
        if($notifi){
            $updated = DB::table('zoolife_notification')->where('id', '=', $notificationId)->delete();
            if ($updated) {
                $dr['error'] = false;
                $dr['status'] = '200';
                $dr['message'] = 'notification deleted successful';
                return Response::json($dr);
            }
        }else{
            $dr['error'] = true;
            $dr['status'] = '101';
            $dr['message'] = 'No notification exist with this Id';
            return Response::json($dr);
        }
    }
}
