<?php

namespace App\Helpers;

class CommonHelper
{
    public static function getPostTime($dateTime, $format = "")
    {
        $returnTime = "";
        if (!empty($dateTime)) {
            $current = date('Y-m-d H:i:s');

            if (!empty($format)) {
                $returnTime = date($format, strtotime($dateTime));
            } else {
                $returnTime = date('D m, Y', strtotime($dateTime));
            }
        }

        return $returnTime;
    }

    public static function getWebUrl($url, $type = "")
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) { 
            return $url;
        } if (!empty($type)) {
            $path = "";
            if (in_array($type, ['ad', 'ad_video', 'category', 'slider', 'article'])) {
                $path = "/uploads/". $type . "/";
            }
            return asset($path . $url);
        }else {
            return asset($url);
        }
    }

}
