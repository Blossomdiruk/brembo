<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\RegisterComplaint;
use App\Models\ComplaintStatus;
use App\Models\User;
use App\Models\EventTitle;
use Carbon\Carbon;
use App\Models\LabourOfficeDivision;
use App\Models\ComplaintHistory;
use App\Models\SmsTemplate;
use App\Models\MailTemplate;
use DB;
use App\Library\MobitelSms;

class EventController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:event-list|event-edit', ['only' => ['list']]);
        $this->middleware('permission:event-edit', ['only' => ['edit', 'update']]);
    }

    public function list(Request $request)
    {
        $office_id = Auth::user()->office_id;

        $labouroffices = LabourOfficeDivision::get();

        if ($request->ajax()) {
            $query = Event::select('events.*', 'register_complaints.complainant_f_name', 'register_complaints.ref_no', 'register_complaints.external_ref_no')
                ->join('register_complaints', 'register_complaints.id', '=', 'events.complaint_id')
                ->where('events.event_date', '>' , Carbon::now()->format('Y-m-d'));
                // ->where('events.end_time', '>' , Carbon::now()->format('H:i:s'))
            if (!empty($request->office_id)) {
                $query->where('register_complaints.current_office_id', '=', $request->office_id);
            }
            if($office_id != 83) {
                $query->where('register_complaints.current_office_id', $office_id);
            }
            $data = $query->orderBy('events.id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-event/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['edit'])
                ->make(true);
        }

        return view('adminpanel.event.list', ['labouroffices' => $labouroffices]);
    }

    public function edit($id)
    {
        $recID = decrypt($id);
        $data = Event::find($recID);

        $complainID = decrypt($id);
        $complaint = RegisterComplaint::find($data->complaint_id);
        $officer_id = Auth::user()->id;

        $complaintstatus = ComplaintStatus::where('status','Y')->where('is_delete',0)->get();

        $officerlo = User::where('id', $complaint->lo_officer_id)->first();

        $eventtitles = EventTitle::where('status', 'Y')->where('is_delete', 0)->get();
        
        return view('adminpanel.event.edit', ['complaint' => $complaint, 'data' => $data, 'officer_id' => $officer_id, 'complaintstatus' => $complaintstatus, 'officerlo' => $officerlo, 'eventtitles' => $eventtitles]);
    }


    public function update(Request $request)
    {
        try {
            DB::beginTransaction(); 

        $request->validate([
            'event_title' => 'required',
            'event_date' => 'required',
            'status_id' => 'required'
        ]);

        $data =  Event::find($request->id);
        $data->event_title = $request->event_title;
        $data->event_date = $request->event_date;
        $data->start_time = $request->start_time;
        $data->end_time = $request->end_time;
        $data->status_id = $request->status_id;
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Event record '.$data->event_title.' updated('.$id.').');

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();
        $complaintstatus = ComplaintStatus::where('id', $request->status_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }

        $timesplit = explode(':', $request->start_time);

        if($timesplit[0] >= 12) {
            $AMPM = "PM";
        } else {
            $AMPM = "AM";
        }

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Create_event';
        $insert['sent_from_office'] = $request->officer_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = NULL;
        $insert['sent_to_office_code'] = NULL;
        $insert['action_type'] = 'Ongoing';
        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['assigned_lo_id'] = $request->lo_id;
        $insert['forward_type_id'] = 0;
        $insert['complaint_status_id'] = $request->status_id;
        $insert['status_des'] =  'Reschedule '.$request->event_title .' - '.$request->event_date .' - '.$request->start_time.' '.$AMPM;

        // if($complaintdetails->pref_lang == "SI") {
        //     $insert['status_des'] = $complaintstatus->status_si;
        // } else if($complaintdetails->pref_lang == "TA") {
        //     $insert['status_des'] = $complaintstatus->status_ta;
        // } else {
        //     $insert['status_des'] = $complaintstatus->status_en;
        // }

        ComplaintHistory::insert($insert);

        $regdata = RegisterComplaint::where('id', $request->complaint_id)
            ->get();

        // start sending mail
        $mailtem = MailTemplate::where('status', 'Y')
                ->where('id', 18)
                ->get();

        $officename = LabourOfficeDivision::where('id',$regdata[0]->current_office_id)->first();

        \App::setLocale($regdata[0]->pref_lang);
    
        if($regdata[0]->complainant_email != ''){
            
            if($regdata[0]->pref_lang == 'EN'){
                $e_sub = $mailtem[0]->mail_template_name_en;
                // $e_body = $mailtem[0]->body_content_en;
                $e_name = $mailtem[0]->mail_template_name_en;

                $complainantname = $regdata[0]->complainant_f_name;
                
                $variables = ['[EVENTNAME]','[DATE]','[STARTINGTIME]','[REFERENCENUMBER]','[OFFICENAME]'];
                
                $variableData = [$complaintstatus->status_en,$request->event_date,$request->start_time,$regdata[0]->external_ref_no,$officename->office_name_en];
                
                $e_body = str_ireplace($variables, $variableData, $mailtem[0]->body_content_en);
                
                $email_body = 'Dear'.' '.$complainantname.', '.$e_body;

            } else if($regdata[0]->pref_lang == 'SI'){
                $e_sub = $mailtem[0]->mail_template_name_sin;
                // $e_body = $mailtem[0]->body_content_sin;
                $e_name = $mailtem[0]->mail_template_name_sin;

                $complainantname = $regdata[0]->complainant_f_name_si;

                $variables = ['[EVENTNAME]','[DATE]','[STARTINGTIME]','[REFERENCENUMBER]','[OFFICENAME]'];

                $variableData = [$complaintstatus->status_si,$request->event_date,$request->start_time,$regdata[0]->external_ref_no,$officename->office_name_sin];

                $e_body = str_ireplace($variables, $variableData, $mailtem[0]->body_content_sin);

                $email_body = 'හිතවත්'.' '.$complainantname.', '.$e_body;

            } else if($regdata[0]->pref_lang == 'TA'){
                $e_sub = $mailtem[0]->mail_template_name_tam;
                // $e_body = $mailtem[0]->body_content_tam;
                $e_name = $mailtem[0]->mail_template_name_tam;

                $complainantname = $regdata[0]->complainant_f_name_ta;

                $variables = ['[EVENTNAME]','[DATE]','[STARTINGTIME]','[REFERENCENUMBER]','[OFFICENAME]'];

                $variableData = [$complaintstatus->status_ta,$request->event_date,$request->start_time,$regdata[0]->external_ref_no,$officename->office_name_tam];

                $e_body = str_ireplace($variables, $variableData, $mailtem[0]->body_content_tam);

                $email_body = 'அன்பார்ந்த'.' '.$complainantname.', '.$e_body;
            }
            
            \Mail::send('mail.complaint-mail',
                array(
                    'ref_no' => $regdata[0]->external_ref_no,
                    'date' => $regdata[0]->created_at,
                    'name' => $complainantname,
                    'subject' => $e_sub,
                    'body' => $email_body,
                    ), function($message) use ($regdata,$e_name)
                    {
                    $message->from('cms@labourdept.gov.lk');
                    $message->to($regdata[0]->complainant_email)->subject($e_name);
                });

                // \EmailLog::addToLog($complainantname, $regdata[0]->complainant_email, $e_sub, $email_body);

                // end sending mail
        }

        if($regdata[0]->complainant_mobile != ''){

            $smsitem = SmsTemplate::where('status', 'Y')
                ->where('is_delete', 0)
                ->where('id', 9)
                ->first();

            // $complainant_f_name = $request->complainant_f_name;

            if($regdata[0]->pref_lang == 'EN'){
                $s_sub = $smsitem->sms_template_name_en;
                // $s_body = $smsitem->body_content_en;

                $variables = ['[EVENTNAME]','[DATE]','[STARTINGTIME]','[REFERENCENUMBER]','[OFFICENAME]','[COMPLAINANTNAME]'];

                $variableData = [$complaintstatus->status_en,$request->event_date,$request->start_time,$regdata[0]->external_ref_no,$officename->office_name_en,$regdata[0]->complainant_f_name.' '.$regdata[0]->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem->body_content_en);

                $sms_body = $s_body;

            } else if($regdata->pref_lang == 'SI'){
                $s_sub = $smsitem->sms_template_name_sin;
                // $s_body = $smsitem->body_content_sin;

                $variables = ['[EVENTNAME]','[DATE]','[STARTINGTIME]','[REFERENCENUMBER]','[OFFICENAME]','[COMPLAINANTNAME]'];

                $variableData = [$complaintstatus->status_si,$request->event_date,$request->start_time,$regdata[0]->external_ref_no,$officename->office_name_en,$regdata[0]->complainant_f_name_si.' '.$regdata[0]->complainant_l_name_si];

                $s_body = str_ireplace($variables, $variableData, $smsitem->body_content_sin);

                $sms_body = $s_body;

            } else if($regdata->pref_lang == 'TA'){
                $s_sub = $smsitem->sms_template_name_tam;
                // $s_body = $smsitem->body_content_tam;

                $variables = ['[EVENTNAME]','[DATE]','[STARTINGTIME]','[REFERENCENUMBER]','[OFFICENAME]','[COMPLAINANTNAME]'];

                $variableData = [$complaintstatus->status_ta,$request->event_date,$request->start_time,$regdata[0]->external_ref_no,$officename->office_name_en,$regdata[0]->complainant_f_name_ta.' '.$regdata[0]->complainant_l_name_ta];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                $sms_body = $s_body;
            }

            $mobitelSms = new MobitelSms();
            $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
            $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($regdata[0]->complainant_mobile),0);
            $mobitelSms->closeSession($session);
        }

        DB::commit();
        return redirect()->route('event-list')
            ->with('success', 'Record updated successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }
}

