<?php

namespace App\Http\Controllers\Adminpanel;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\MobitelSms;
use App\Models\MultipleSmsEmailRecord;

class MultipleSmsRecordController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:send-sms', ['only' => ['index']]);

    }

    public function index()
    {

        $mobile = MultipleSmsEmailRecord::pluck('mobile')->toArray();

        $message = 'Test SMS Message';

        $mobitelSms = new MobitelSms();
        $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
        $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$message,$mobile,0);
        $mobitelSms->closeSession($session);

        return redirect()->route('dashboard');
    }

    public function email()
    {

        $email = MultipleSmsEmailRecord::pluck('email')->toArray();

        $subject = 'Test Email';
        $message = 'Test Email Message';

        foreach($email as $item){

            if($item){
                if (filter_var($item, FILTER_VALIDATE_EMAIL)) {
                    \Mail::send('mail.sample-mail',
                        array(
                            'body' => $message,
                        ), function($message) use ($subject, $item)
                    {
                        $message->to($item)->subject($subject);
                    });
                }
            }
            
        }
        

        return redirect()->route('dashboard');
    }

}
