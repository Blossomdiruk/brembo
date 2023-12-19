<?php


namespace App\Helpers;
use Request;
use App\Models\LogActivity as LogActivityModel;


class LogActivity
{


    public static function addToLog($subject)
    {
		$ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARD_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARD_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

    	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = $ip;
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
		$log['status'] = 'Y';
    	LogActivityModel::create($log);

    }

    public static function get_last_activity() {
        $user_id = auth()->user()->id;
        $sql = LogActivityModel::select('created_at')
            ->where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!empty($sql)) {
            return $sql->created_at;
        } else {
            return NULL;
        }
    }

}