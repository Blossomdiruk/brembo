<?php


namespace App\Helpers;
use Request;
use App\Models\EmailLog as EmailLogModel;


class EmailLog
{

    public static function addToLog($name, $email, $subject, $body)
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
        $log['name'] = $name;
    	$log['email'] = $email;
        $log['body'] = $body;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = $ip;
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
		$log['status'] = 'Y';
    	EmailLogModel::create($log);

    }

}
