<?php

namespace App\Traits;

trait CommonHelperTrait
{
    public function sendWhatsappMessage($phone_number, $message = '')
    {
        $endpoint = "https://user.4whats.net/api/sendMessage";
        $instanceId = "133175";
        $token = "6d04942e-a46d-4fd0-b7c9-a33a132b89d5";
        
        $data = array(
            'instanceid' => $instanceId,
            'token' => $token,
            'phone' => $phone_number,
            'body' => $message
        );
        
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            // die(curl_error($ch));
            return false;
        } else {
            return true;
        }
        
        curl_close($ch);
    }
}