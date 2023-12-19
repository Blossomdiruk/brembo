<?php

namespace App\Http\Controllers\Adminpanel;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestEmailController extends Controller
{
    function __construct()
    {

    }

    public function index()
    {
        $subject = 'Test Email';
       $body = '<p>Test Email Message </p>'; 
     echo  date('H:i:s');
       exit();

       $status = \Mail::send([],[], function($message) use ($subject, $body)
        {
            $message->to('thushara@tekgeeks.net')
            ->subject($subject)
            ->setBody($body, 'text/html');
        });
        var_dump($status);
        exit();
       // return redirect()->route('dashboard');
    }

}
