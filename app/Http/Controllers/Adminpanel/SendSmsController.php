<?php

namespace App\Http\Controllers\Adminpanel;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\MobitelSms;

class SendSmsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:send-sms', ['only' => ['index']]);

    }

    public function index()
    {

        return view('adminpanel.sendsms.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'complainant_mobile' => 'required',
            'message' => 'required'
        ]);

        $complainant_mobile = array();

        $count = count($request->complainant_mobile) - 1;

        for ($i = 0; $i < $count; $i++) {
            if($request->complainant_mobile[$i] != null){
                $complainant_mobile[$i] = $request->complainant_mobile[$i];
                \SmsLog::addToLog('', $request->complainant_mobile[$i], $request->message);
            }   
        }
        //dd($complainant_mobile);

        $mobitelSms = new MobitelSms();
        $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
        $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$request->message,$complainant_mobile,0);
        $mobitelSms->closeSession($session);

        return redirect()->route('send-sms')
            ->with('success', 'SMS sent successfully.');
    }

}
