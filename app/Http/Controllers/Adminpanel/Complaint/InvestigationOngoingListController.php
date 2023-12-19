<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use DataTables;

use Illuminate\Http\Request;
use App\Models\RegisterComplaint;
use App\Models\Event;
use App\Models\MailTemplate;
use App\Http\Controllers\Controller;
use App\Models\MailHistory;
use App\Models\ComplaintDocument;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Models\ComplaintHistory;
use App\Models\LabourOfficeDivision;
use Carbon\Carbon;
use App\Library\MobitelSms;
use App\Models\ComplaintStatus;
use App\Models\EventTitle;
use App\Models\SmsTemplate;
use App\Models\GratuityDetails;
use App\Models\MinimumWageMain;
use App\Models\MinimumWageDetail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class InvestigationOngoingListController extends Controller
{
    function __construct()
    {
       $this->middleware('permission:investigation-ongoing-list|calendar|mail', ['only' => ['list']]);
       $this->middleware('permission:calendar', ['only' => ['calendar', 'createEvent']]);
       $this->middleware('permission:mail', ['only' => ['mail', 'printview', 'print', 'saveMail']]);
    }

    public function list(Request $request)
    {
        $userrole = Auth::user()->roles->pluck('name')->first();

        $office_id = Auth::user()->office_id;

        $user_id = Auth::user()->id;

        $assignCount = RegisterComplaint::where('register_complaints.lo_officer_id', $user_id)
            ->where('register_complaints.current_office_id', $office_id)
            ->where('register_complaints.action_type', '<>', 'Closed')
            ->where('register_complaints.action_type', '<>', 'Pending_approve')
            ->where('register_complaints.action_type', '<>', 'Pending_legal')
            ->where('register_complaints.action_type', '<>', 'Tempclosed')
            ->count();

        $pendingCount = RegisterComplaint::where('action_type', 'Pending')
            ->where('current_office_id', $office_id)
            ->count();

        $ongoingCount = RegisterComplaint::where('action_type', 'Ongoing')
            ->where('current_office_id', $office_id)
            ->count();

        $tempClosedCount = RegisterComplaint::where('action_type', 'Tempclosed')
            ->where('current_office_id', $office_id)
            ->count();

        $closedCount = RegisterComplaint::where('action_type', 'Closed')
            ->where('current_office_id', $office_id)
            ->count();

        $certificateCount = RegisterComplaint::where('action_type', 'Pending_legal')
            ->where('current_office_id', $office_id)
            ->count();

        $chargesheetCount = RegisterComplaint::where('action_type', 'Pending_plaint_charge_sheet')
            ->where('current_office_id', $office_id)
            ->count();

        $recoveryCount = RegisterComplaint::where('action_type', 'Pending_recovery')
            ->where('current_office_id', $office_id)
            ->count();

        $appealCount = RegisterComplaint::where('action_type', 'Waiting')
            ->where('current_office_id', $office_id)
            ->count();

        $pendingApprovalCount = RegisterComplaint::where('action_type','Pending_approve')
            ->where('current_office_id',$office_id)
            ->count();

        $labouroffice = LabourOfficeDivision::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('office_name_en', 'ASC')
            ->get();

            $count = 0;
            foreach($labouroffice as $key => $item){

                $maternityBenLeave[$key] = RegisterComplaint::whereRaw("find_in_set('8', complain_category)")
                ->where('complaint_status', '<>', 'Closed')
                    ->where('current_office_id', $item->id)
                    ->count();

                $childLabour[$key] = RegisterComplaint::whereRaw("find_in_set('16', complain_category)")
                    ->where('complaint_status','<>', 'Closed')
                        ->where('current_office_id', $item->id)
                        ->count();

                $femaleEmpNight[$key] = RegisterComplaint::whereRaw("find_in_set('7', complain_category)")
                        ->where('complaint_status', '<>', 'Closed')
                            ->where('current_office_id', $item->id)
                            ->count();

                // if(Session::get('applocale') == 'ta'){
                //     $office_name = $item->office_name_tam;
                // } else if (Session::get('applocale') == 'si'){
                //     $office_name = $item->office_name_sin;
                // } else {
                //     $office_name = $item->office_name_en;
                // }

                $count += $maternityBenLeave[$key] + $childLabour[$key] + $femaleEmpNight[$key];

                $data[] = array(
                    "id" => $item->id,
                    "office" => $item->office_name_en,
                    'maternity_ben_leave' => $maternityBenLeave[$key],
                    'child_labour' => $childLabour[$key],
                    'female_emp_night' => $femaleEmpNight[$key]
                );



            }

            $totalWcaComplaint = $count;

        if ($request->ajax()) {
            $office_id = Auth::user()->office_id;
            $data = RegisterComplaint::select('*')
                ->where('action_type', 'Ongoing')
                ->where('current_office_id', $office_id);
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                        ->count();
                    $edit_url = "";
                    $status_url = url('/complaint-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> ' .$statusCount;
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.actionsStatus')
                ->addColumn('action', 'adminpanel.complaint.actionsAction')
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                })
                ->addColumn('mail', function ($row) {
                    $mail_url = url('/mail/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $mail_url . '" title="letter"><i class="fa fa-envelope" ></i><span class="air-top-right txt-color-red padding-3" >&nbsp;</span></a>';
                    return $btn;
                })
                ->addColumn('calendar', function ($row) {
                    $calendar_url = url('/calendar/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $calendar_url . '" title="calendar"><i class="fa fa-calendar " ></i><span class="air-top-right txt-color-red padding-3" >&nbsp;</span></a>';
                    return $btn;
                })
                ->addColumn('calculation', function ($row) {
                    $calculation_url = url('/calculation/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $calculation_url . '" title="calculation"><i class="fa fa-calculator" ></i><span class="air-top-right txt-color-red padding-3" >&nbsp;</span></a>';
                    return $btn;
                })
                ->addColumn('upload', function ($row) {
                    $update_url = url('/upload-document/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $update_url . '" title="upload"><i class="fa fa-upload " ></i><span class="air-top-right txt-color-red padding-3" >&nbsp;</span></a>';
                    return $btn;
                })
                ->addColumn('modify', function ($row) {
                    $edit_url = url('/edit-register-complaint/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $edit_url . '" title="Modify"><i class="glyphicon glyphicon-edit"></i></a> ';
                    return $btn;
                })
                ->addColumn('view', function ($row) {
                    $view_url = url('/view-ongoing-complaint/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->addColumn('online_manual', function ($row) {
                    $complaint = ComplaintHistory::where('complaint_id', $row->id)
                            ->orderBy('created_at', 'asc')->first();

                    if($complaint->user_id != 0){
                        $btn = 'M';
                    } else {
                        $btn = 'O';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'mail', 'calendar','calculation', 'upload', 'modify', 'view', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.investigation_ongoing_list', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount, 'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

    public function calendar($id)
    {

        $complainID = decrypt($id);
        $data = RegisterComplaint::find($complainID);
        $officer_id = Auth::user()->id;

        $complaintstatusdetails = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'desc')->first();

        $complaintstatus = ComplaintStatus::where('status','Y')->where('is_delete',0)->get();

        $officerlo = User::where('id', $data->lo_officer_id)->first();

        // if($request->ajax())
        // {
        // 	$data = Event::whereDate('event_date', '>=', $request->event_date)
        //                ->get(['id', 'event_title', 'event_date']);
        //     return response()->json($data);
        // }

        // $events_cal = Event::where('complaint_id',  $complainID)->get();
        if(!empty($officerlo))
        {
            $events_cal = Event::join('register_complaints', 'register_complaints.id', '=', 'events.complaint_id')->where('events.officer_id',  $officerlo->id)->get();

        } else {
            $events_cal = Event::join('register_complaints', 'register_complaints.id', '=', 'events.complaint_id')->where('complaint_id',  $complainID)->get();

        }

        $eventtitles = EventTitle::where('status', 'Y')->where('is_delete', 0)->get();

// dd($events_cal);
        //var_dump($events_cal); exit();

        return view('adminpanel.complaint.calendar', ['data' => $data, 'officer_id' => $officer_id, 'events_cal' => $events_cal, 'complaintstatus' => $complaintstatus, 'officerlo' => $officerlo, 'eventtitles' => $eventtitles, 'last_complaint_sataus' => $complaintstatusdetails->complaint_status_id]);
    }

    public function view($id)
    {
        $complainID = decrypt($id);

        $data = RegisterComplaint::with('provinces', 'districts', 'establishments', 'labouroffices')->find($complainID);

        $complaintdocuments = ComplaintDocument::where('ref_no', $complainID)->get();

        $complaintstatusdetails = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'desc')->get();

        return view('adminpanel.complaint.view', ['data' => $data, 'complaintdocuments' => $complaintdocuments, 'complaintstatusdetails' => $complaintstatusdetails]);
    }

    public function createEvent(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'event_title' => 'required',
            'event_date' => 'required',
            'status_id' => 'required',
        ]);

        $newevent = new Event();
        $newevent->complaint_id = $request->complaint_id;
        $newevent->officer_id = $request->lo_id;
        $newevent->event_title = $request->event_title;
        $newevent->event_date = $request->event_date;
        $newevent->start_time = $request->start_time;
        $newevent->end_time = $request->end_time;
        $newevent->status_id = $request->status_id;
        $newevent->save();

        // dd($newevent);

        $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();
        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        $complaintstatus = ComplaintStatus::where('id', $request->status_id)->first();

        // dd($complaintstatus);

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
        $insert['status_des'] =  $request->event_title .' - '.$request->event_date .' - '.$request->start_time.' '.$AMPM;

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
                ->where('id', 8)
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

                \EmailLog::addToLog($complainantname, $regdata[0]->complainant_email, $e_sub, $email_body);

                // end sending mail
        }

        if($regdata[0]->complainant_mobile != ''){

            $smsitem = SmsTemplate::where('status', 'Y')
                ->where('is_delete', 0)
                ->where('id', 5)
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
        return redirect()->back()
            ->with('success', 'Event created successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function mail($id)
    {

        $complainID = decrypt($id);
        $data = RegisterComplaint::find($complainID);
        $officer_id = Auth::user()->id;

        $mailtemplates = MailTemplate::where('status', 'Y')->where('is_delete', '0')->get();

        $mailhistories = MailHistory::with(['mailtemplatedetails', 'userdetails', 'complaintdetails'])->where('complaint_id', $complainID)->where('status', 'Email')->orWhere('status', 'Print/Email')->latest()->take(3)->get();

        $letterhistories = MailHistory::with(['mailtemplatedetails', 'userdetails', 'complaintdetails'])->where('complaint_id', $complainID)->where('status', 'Letter')->orWhere('status', 'Print/Letter')->latest()->take(3)->get();

        $ndhistories = MailHistory::with(['mailtemplatedetails', 'userdetails', 'complaintdetails'])->where('complaint_id', $complainID)->where('status', 'ND')->orWhere('status', 'Print/ND')->latest()->take(3)->get();
        // if($request->ajax())
        // {
        // 	$data = Event::whereDate('event_date', '>=', $request->event_date)
        //                ->get(['id', 'event_title', 'event_date']);
        //     return response()->json($data);
        // }

        return view('adminpanel.complaint.mail', ['data' => $data, 'officer_id' => $officer_id, 'mailtemplates' => $mailtemplates, 'mailhistories' => $mailhistories, 'letterhistories' => $letterhistories , 'ndhistories' => $ndhistories, 'tab' => 's1']);
    }

    public function getcomplainantDetails($prefLang, $id)
    {
        if ($prefLang == "SI") {
            $complainantName = "complainant_f_name_si";
            $complainantAddress = "complainant_address_si";
            $employerName = "employer_name_si";
            $employerAddress = "employer_address_si";
        } elseif ($prefLang == "TA") {
            $complainantName = "complainant_f_name_ta";
            $complainantAddress = "complainant_address_ta";
            $employerName = "employer_name_ta";
            $employerAddress = "employer_address_ta";
        } else {
            $complainantName = "complainant_f_name";
            $complainantAddress = "complainant_address";
            $employerName = "employer_name";
            $employerAddress = "employer_address";
        }

        $complainantDetails = RegisterComplaint::select($complainantName,$complainantAddress,$employerName,$employerAddress)->where('id', $id)->first();

        return response()->json($complainantDetails);
    }

    public function getLetterTempTitle($prefLang, $category, $id)
    {
        if ($prefLang == "SI") {
            $letterTemp = "mail_template_name_sin";
            $complainantname = "complainant_f_name_si";
        } else if ($prefLang == "TA") {
            $letterTemp = "mail_template_name_tam";
            $complainantname = "complainant_f_name_ta";
        } else {
            $letterTemp = "mail_template_name_en";
            $complainantname = "complainant_f_name";
        }

        if ($category == "L") {
            $letterTemp = MailTemplate::select('id',$letterTemp)->where('category','L')->where('status','Y')->where('is_delete',0)->get();
        }else if($category == "E") {
            $letterTemp = MailTemplate::select('id',$letterTemp)->where('category','E')->where('status','Y')->where('is_delete',0)->get();
        }else if($category == "ND"){
            $letterTemp = MailTemplate::select('id',$letterTemp)->where('category','ND')->where('status','Y')->where('is_delete',0)->get();
        }
        return response()->json($letterTemp);
    }

    public function getletterTemplates($letterTemplateID, $prefLang, $id)
    {

        $complaintdetails = RegisterComplaint::where('id', $id)->first();

        $officername = User::where('id', $complaintdetails->lo_officer_id)->first();

        $officename = LabourOfficeDivision::where('id',$complaintdetails->current_office_id)->first();

        // $investigationevent = Event::where('complaint_id', $id)->where('status_id', 6)->orWhere('status_id', 19)->latest()->first();
        $investigationevent = Event::where('complaint_id', $id)->latest()->first();

        $gratuityDetails = GratuityDetails::where('complaint_id', $id)->first();

        $complaintcreatedat = $complaintdetails->created_at;
        $comprecdate = $complaintcreatedat->toDateString();

        if($letterTemplateID == 14 || $letterTemplateID == 19 || $letterTemplateID == 16 || $letterTemplateID == 17) {
            if($investigationevent != '') {
                $eventdate = $investigationevent->event_date;
                $time = $investigationevent->start_time;
            } else {
                $eventdate = '[DATE]';
                $time = '[TIME]';
            }
        } else {
            $eventdate = '[DATE]';
            $time = '[TIME]';
        }

        if($letterTemplateID == 7){
            if($gratuityDetails){
                $years = $gratuityDetails->working_years;
                $gratuityamount = number_format($gratuityDetails->gratuity_amount, 2);
                if($gratuityDetails->gratuity_due_date != ''){
                    $gratuityduedate = $gratuityDetails->gratuity_due_date;
                } else {
                    $gratuityduedate = "[YYYY].[MM].[DD]";
                }
                $percentage = $gratuityDetails->surcharge_percentage;
                $surcharge = number_format($gratuityDetails->surcharge, 2);
                if($gratuityDetails->gratuity_paid_amount != '' && $gratuityDetails->gratuity_paid_amount != 0){
                    $balgratuityamount = "(".number_format($gratuityDetails->gratuity_amount, 2)." - ".number_format($gratuityDetails->gratuity_paid_amount, 2).")";
                } else {
                    $balgratuityamount = number_format($gratuityDetails->gratuity_amount, 2);
                }
                $totalgratutity = number_format($gratuityDetails->total_gratuity, 2);
                if($gratuityDetails->wage_type == 1){
                    $lastsalary = number_format($gratuityDetails->received_sal, 2);
                    $calvalue = "(".number_format($gratuityDetails->received_sal, 2)."/ 2)";
                } else if($gratuityDetails->wage_type == 2){
                    $lastsalary = number_format($gratuityDetails->received_sal, 2);
                    $calvalue = "".number_format($gratuityDetails->received_sal, 2)."x 14";
                } else if($gratuityDetails->wage_type == 3){
                    $lastsalary = number_format($gratuityDetails->received_sal, 2);
                    $calvalue = "(".number_format($gratuityDetails->received_sal, 2)."/".$gratuityDetails->working_days.") x 14";
                }
    
                $wordtotalgratutity = $this->convert_number_to_words($totalgratutity);
            } else {
                $years = '[YEARS]';
                $gratuityamount = '[GRATUITYAMOUNT]';
                $gratuityduedate = '[GRATUITYDUEDATE]';
                $percentage = '[PERCENTAGE]';
                $surcharge = '[SURCHARGE]';
                $totalgratutity = '[TOTALGRATUITY]';
                $lastsalary = '[LASTSALARY]';
                $calvalue = '[CALVALUE]';
                $wordtotalgratutity = '[TOTALGRATUITY]';
                $balgratuityamount = '[BALGRATUITYAMOUNT]';
            }
            
        } else {
            $years = '[YEARS]';
            $gratuityamount = '[GRATUITYAMOUNT]';
            $gratuityduedate = '[GRATUITYDUEDATE]';
            $percentage = '[PERCENTAGE]';
            $surcharge = '[SURCHARGE]';
            $totalgratutity = '[TOTALGRATUITY]';
            $lastsalary = '[LASTSALARY]';
            $calvalue = '[CALVALUE]';
            $wordtotalgratutity = '[TOTALGRATUITY]';
            $balgratuityamount = '[BALGRATUITYAMOUNT]';
            
        }

        if ($prefLang == "SI") {
            $colName = "body_content_sin";
            $subject = "mail_template_name_sin";

            $mailtemplatess = MailTemplate::select('id',$colName,$subject)->where("id", $letterTemplateID)->get();

            if($complaintdetails->title == 1) {
                $title = 'මහතා';
            } else if($complaintdetails->title == 2) {
                $title = 'මෙනවිය';
            } else if($complaintdetails->title == 3) {
                $title = 'මහත්මිය';
            } else if($complaintdetails->title == 4) {
                $title = 'ගරු';
            } else {
                $title = 'ආචාර්ය';
            }

            if($officername == "") {
                $officerLo = "[LONAME]";
            } else {
                $officerLo = $officername->name_si;
            }

            if($complaintdetails->join_date == "" && $complaintdetails->terminate_date == "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = "[TERMINATIONDATE]";
            } else if ($complaintdetails->join_date != "" && $complaintdetails->terminate_date == "") {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = "[TERMINATIONDATE]";
            } else if($complaintdetails->join_date == "" && $complaintdetails->terminate_date != "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = $complaintdetails->terminate_date;
            } else {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = $complaintdetails->terminate_date;
            }

            $complainantname = $complaintdetails->complainant_f_name_si.' '.$complaintdetails->complainant_l_name_si;

            $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]', '[YEARS]', '[LASTSALARY]', '[CALVALUE]', '[GRATUITYAMOUNT]', '[GRATUITYDUEDATE]', '[PERCENTAGE]', '[SURCHARGE]', '[TOTALGRATUITY]', '[TOTALGRATUITYINWORD]', '[BALGRATUITYAMOUNT]'];

            $variableData = [$title,$complaintdetails->current_employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_si,$officerLo,$officename->office_name_sin,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address_si,$joineddate,$terminationdate,$eventdate,$time,$comprecdate,$years,$lastsalary,$calvalue,$gratuityamount,$gratuityduedate,$percentage,$surcharge,$totalgratutity,$wordtotalgratutity,$balgratuityamount];

            $replace = str_ireplace($variables, $variableData, $mailtemplatess);

        } elseif ($prefLang == "TA") {
            $colName = "body_content_tam";
            $subject = "mail_template_name_tam";

            $mailtemplatess = MailTemplate::select('id',$colName,$subject)->where("id", $letterTemplateID)->get();

            if($complaintdetails->title == 1) {
                $title = 'திரு ';
            } else if($complaintdetails->title == 2) {
                $title = 'செல்வி ';
            } else if($complaintdetails->title == 3) {
                $title = 'திருமதி ';
            } else if($complaintdetails->title == 4) {
                $title = 'கௌரவ ';
            } else {
                $title = 'டாக்டர் ';
            }

            if($officername == "") {
                $officerLo = "[LONAME]";
            } else {
                $officerLo = $officername->name_ta;
            }

            if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = "[TERMINATIONDATE]";
            } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = "[TERMINATIONDATE]";
            } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = $complaintdetails->terminate_date;
            } else {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = $complaintdetails->terminate_date;
            }

            $complainantname = $complaintdetails->complainant_f_name_ta.' '.$complaintdetails->complainant_l_name_ta;

            $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

            $variableData = [$title,$complaintdetails->current_employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_ta,$officerLo,$officename->office_name_tam,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address_ta,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

            $replace = str_ireplace($variables, $variableData, $mailtemplatess);

        } else {
            $colName = "body_content_en";
            $subject = "mail_template_name_en";

            $mailtemplatess = MailTemplate::select('id',$colName,$subject)->where("id", $letterTemplateID)->get();

            if($complaintdetails->title == 1) {
                $title = 'Mr. ';
            } else if($complaintdetails->title == 2) {
                $title = 'Miss. ';
            } else if($complaintdetails->title == 3) {
                $title = 'Mrs. ';
            } else if($complaintdetails->title == 4) {
                $title = 'Rev. ';
            } else {
                $title = 'Dr. ';
            }

            if($officername == "") {
                $officerLo = "[LONAME]";
            } else {
                $officerLo = $officername->name;
            }

            if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = "[TERMINATIONDATE]";
            } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = "[TERMINATIONDATE]";
            } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = $complaintdetails->terminate_date;
            } else {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = $complaintdetails->terminate_date;
            }

            $complainantname = $complaintdetails->complainant_f_name.' '.$complaintdetails->complainant_l_name;

            $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

            $variableData = [$title,$complaintdetails->current_employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address,$officerLo,$officename->office_name_en,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

            $replace = str_ireplace($variables, $variableData, $mailtemplatess);
        }

        return json_decode($replace);
    }


        /*********** LETTER SAVE FUNCTIONALITY **************/

    // public function saveLetter(Request $request)
    // {
    //     $request->validate([
    //         // 'template_id' => 'required',
    //         // 'sent_to' => 'required',
    //         // 'subject' => 'required',
    //         // 'mail_body' => 'required'
    //     ]);

    //     $mailhistory = new MailHistory();
    //     $mailhistory->template_id = $request->letter_template_id;
    //     $mailhistory->sent_by = Auth::user()->id;
    //     $mailhistory->sent_to = $request->letter_complainant_name;
    //     $mailhistory->status = 'Letter';
    //     $mailhistory->complaint_id = $request->complaint_id;

    //     $lang = $request->pref_lang_let;

    //     $mailtemplatedetails = MailTemplate::where('id', $request->letter_template_id)->first();

    //     $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();

    //     $officename = LabourOfficeDivision::where('id',$complaintdetails->current_office_id)->first();

    //     $officername = User::where('id', $complaintdetails->lo_officer_id)->first();

    //     if ($lang == 'SI') {
    //         $mail_subject = $mailtemplatedetails->mail_template_name_sin;
    //     } elseif ($lang == "TA") {
    //         $mail_subject = $mailtemplatedetails->mail_template_name_tam;
    //     } else {
    //         $mail_subject = $mailtemplatedetails->mail_template_name_en;
    //     }

    //     if($request->letter_for == "Employer") {
    //         if ($lang == 'SI') {

    //             if($complaintdetails->title == 1) {
    //                 $title = 'Mr. ';
    //             } else if($complaintdetails->title == 2) {
    //                 $title = 'Miss. ';
    //             } else if($complaintdetails->title == 3) {
    //                 $title = 'Mrs. ';
    //             } else if($complaintdetails->title == 4) {
    //                 $title = 'Rev. ';
    //             } else {
    //                 $title = 'Dr. ';
    //             }

    //             $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]'];

    //             $variableData = [$title,$complaintdetails->employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->employer_address_si,$officername->name,$officename->office_name_sin];

    //             $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_sin);

    //             $mail_content = $replace;

    //         } elseif ($lang == "TA") {

    //             if($complaintdetails->title == 1) {
    //                 $title = 'Mr. ';
    //             } else if($complaintdetails->title == 2) {
    //                 $title = 'Miss. ';
    //             } else if($complaintdetails->title == 3) {
    //                 $title = 'Mrs. ';
    //             } else if($complaintdetails->title == 4) {
    //                 $title = 'Rev. ';
    //             } else {
    //                 $title = 'Dr. ';
    //             }

    //             $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]'];

    //             $variableData = [$title,$complaintdetails->title,$complaintdetails->employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->employer_address_ta,$officername->name,$officename->office_name_tam];

    //             $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_tam);

    //             $mail_content = $replace;

    //         } else {

    //             if($complaintdetails->title == 1) {
    //                 $title = 'Mr. ';
    //             } else if($complaintdetails->title == 2) {
    //                 $title = 'Miss. ';
    //             } else if($complaintdetails->title == 3) {
    //                 $title = 'Mrs. ';
    //             } else if($complaintdetails->title == 4) {
    //                 $title = 'Rev. ';
    //             } else {
    //                 $title = 'Dr. ';
    //             }

    //             $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]'];

    //             $variableData = [$title,$complaintdetails->title,$complaintdetails->employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->employer_address_ta,$officername->name,$officename->office_name_en];

    //             $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_en);

    //             $mail_content = $complaintdetails->employer_address.'<br><br>'.$replace;
    //         }
    //     } else  {
    //         if ($lang == 'SI') {

    //             if($complaintdetails->title == 1) {
    //                 $title = 'Mr. ';
    //             } else if($complaintdetails->title == 2) {
    //                 $title = 'Miss. ';
    //             } else if($complaintdetails->title == 3) {
    //                 $title = 'Mrs. ';
    //             } else if($complaintdetails->title == 4) {
    //                 $title = 'Rev. ';
    //             } else {
    //                 $title = 'Dr. ';
    //             }

    //             $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]'];

    //             $variableData = [$title,$complaintdetails->employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->employer_address_si,$officername->name,$officename->office_name_sin];

    //             $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_sin);

    //             $mail_content = $replace;
    //         } elseif ($lang == "TA") {

    //             if($complaintdetails->title == 1) {
    //                 $title = 'Mr. ';
    //             } else if($complaintdetails->title == 2) {
    //                 $title = 'Miss. ';
    //             } else if($complaintdetails->title == 3) {
    //                 $title = 'Mrs. ';
    //             } else if($complaintdetails->title == 4) {
    //                 $title = 'Rev. ';
    //             } else {
    //                 $title = 'Dr. ';
    //             }

    //             $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]'];

    //             $variableData = [$title,$complaintdetails->title,$complaintdetails->employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->employer_address_ta,$officername->name,$officename->office_name_tam];

    //             $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_tam);

    //             $mail_content = $replace;
    //         } else {

    //             if($complaintdetails->title == 1) {
    //                 $title = 'Mr. ';
    //             } else if($complaintdetails->title == 2) {
    //                 $title = 'Miss. ';
    //             } else if($complaintdetails->title == 3) {
    //                 $title = 'Mrs. ';
    //             } else if($complaintdetails->title == 4) {
    //                 $title = 'Rev. ';
    //             } else {
    //                 $title = 'Dr. ';
    //             }

    //             $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]'];

    //             $variableData = [$title,$complaintdetails->title,$complaintdetails->employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->employer_address_ta,$officername->name,$officename->office_name_en];

    //             $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_en);

    //             $mail_content = $replace;
    //         }
    //     }

    //     if ($request->mail_body != '') {
    //         $mail_body = $request->mail_body;
    //     } else {
    //         $mail_body = $mail_content;
    //     }
    //     $mailhistory->subject = $mail_subject;
    //     $mailhistory->mail_body = $mail_body;
    //     $mailhistory->recipient = $request->letter_for;
    //     $mailhistory->pref_lang = $request->pref_lang_let;
    //     $mailhistory->address = $request->letter_complainant_address;
    //     // $mailhistory->heading = $request->letter_heading;
    //     $mailhistory->save();

    //     return redirect()->route('investigation-ongoing-list')->with('success', 'Letter sent successfully.');
    // }

        /*********** END LETTER SAVE FUNCTIONALITY **************/


    // public function getMailTemplates($id, $lang)
    // {
    //     if ($lang == "SI") {
    //         $colName = "body_content_sin";
    //         $subject = "mail_template_name_sin";
    //     } elseif ($lang == "TA") {
    //         $colName = "body_content_tam";
    //         $subject = "mail_template_name_tam";
    //     } else {
    //         $colName = "body_content_en";
    //         $subject = "mail_template_name_en";
    //     }

    //     $mailtemplatess = MailTemplate::select($colName, "id", $subject)->where("id", $id)->get();

    //     // $sendDetails = RegisterComplaint

    //     return json_decode($mailtemplatess);
    // }

    public function saveMail(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'template_id' => 'required',
            'sent_to' => 'required',
            'subject' => 'required',
            // 'mail_body' => 'required'
        ]);

        $mailhistory = new MailHistory();
        $mailhistory->template_id = $request->template_id;
        $mailhistory->sent_by = $request->sent_by;
        $mailhistory->sent_to = $request->sent_to;
        $mailhistory->subject = $request->subject;
        $mailhistory->status = 'Email';
        $mailhistory->complaint_id = $request->complaint_id;

        $lang = $request->pref_lang_mail;

        $mailtemplatedetails = MailTemplate::where('id', $request->template_id)->first();
        $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();

        $officename = LabourOfficeDivision::where('id', $complaintdetails->current_office_id)->first();

        $officername = User::where('id', $complaintdetails->lo_officer_id)->first();

        $investigationevent = Event::where('complaint_id', $request->complaint_id)->where('status_id', 6)->orWhere('status_id', 19)->latest()->first();

        $complaintcreatedat = $complaintdetails->created_at;
        $comprecdate = $complaintcreatedat->toDateString();

        if($request->template_id == 14 || $request->template_id == 19) {
            if($investigationevent != '') {
                $eventdate = $investigationevent->event_date;
                $time = $investigationevent->start_time;
            } else {
                $eventdate = '[DATE]';
                $time = '[TIME]';
            }
        } else {
            $eventdate = '[DATE]';
            $time = '[TIME]';
        }

        if ($lang == 'SI') {

            if($complaintdetails->title == 1) {
                $title = 'මහතා';
            } else if($complaintdetails->title == 2) {
                $title = 'මෙනවිය';
            } else if($complaintdetails->title == 3) {
                $title = 'මහත්මිය';
            } else if($complaintdetails->title == 4) {
                $title = 'ගරු';
            } else {
                $title = 'ආචාර්ය';
            }

            if($officername == "") {
                $officerLo = "[LONAME]";
            } else {
                $officerLo = $officername->name_si;
            }

            if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = "[TERMINATIONDATE]";
            } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = "[TERMINATIONDATE]";
            } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = $complaintdetails->terminate_date;
            } else {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = $complaintdetails->terminate_date;
            }

            $complainantname = $complaintdetails->complainant_f_name_si.' '.$complaintdetails->complainant_l_name_si;

            $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

            $variableData = [$title,$complaintdetails->current_employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_si,$officerLo,$officename->office_name_sin,$complaintdetails->designation,$complaintdetails->complainant_f_name_si,$complaintdetails->complainant_address_si,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

            $mail_content = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_sin);

        } elseif ($lang == "TA") {

            if($complaintdetails->title == 1) {
                $title = 'திரு ';
            } else if($complaintdetails->title == 2) {
                $title = 'செல்வி ';
            } else if($complaintdetails->title == 3) {
                $title = 'திருமதி ';
            } else if($complaintdetails->title == 4) {
                $title = 'கௌரவ ';
            } else {
                $title = 'டாக்டர் ';
            }

            if($officername == "") {
                $officerLo = "[LONAME]";
            } else {
                $officerLo = $officername->name_ta;
            }

            if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = "[TERMINATIONDATE]";
            } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = "[TERMINATIONDATE]";
            } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = $complaintdetails->terminate_date;
            } else {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = $complaintdetails->terminate_date;
            }

            $complainantname = $complaintdetails->complainant_f_name_ta.' '.$complaintdetails->complainant_l_name_ta;

            $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

            $variableData = [$title,$complaintdetails->current_employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_ta,$officerLo,$officename->office_name_tam,$complaintdetails->designation,$complaintdetails->complainant_f_name_ta,$complaintdetails->complainant_address_ta,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

            $mail_content = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_tam);

        } else {

            if($complaintdetails->title == 1) {
                $title = 'Mr. ';
            } else if($complaintdetails->title == 2) {
                $title = 'Miss. ';
            } else if($complaintdetails->title == 3) {
                $title = 'Mrs. ';
            } else if($complaintdetails->title == 4) {
                $title = 'Rev. ';
            } else {
                $title = 'Dr. ';
            }

            if($officername == "") {
                $officerLo = "[LONAME]";
            } else {
                $officerLo = $officername->name;
            }

            if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = "[TERMINATIONDATE]";
            } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = "[TERMINATIONDATE]";
            } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                $joineddate = "[JOINEDDATE]";
                $terminationdate = $complaintdetails->terminate_date;
            } else {
                $joineddate = $complaintdetails->join_date;
                $terminationdate = $complaintdetails->terminate_date;
            }

            $complainantname = $complaintdetails->complainant_f_name.' '.$complaintdetails->complainant_l_name;

            $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

            $variableData = [$title,$complaintdetails->current_employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address,$officerLo,$officename->office_name_en,$complaintdetails->designation,$complaintdetails->complainant_f_name,$complaintdetails->complainant_address,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

            $mail_content = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_en);

        }

        if ($request->mail_body != '') {
            $mail_body = $request->mail_body;
        } else {
            $mail_body = $mail_content;
        }

        $mailhistory->mail_body = $mail_body;
        $mailhistory->pref_lang = $request->pref_lang_mail;
        // $mailhistory->heading = $request->mail_heading;
        $mailhistory->save();

        $mailhistoryid = $mailhistory->id;

        $sent_by = $request->sent_by;
        $sent_to = $request->sent_to;
        $subject = $request->subject;
        $mail_body_content = strip_tags($request->mail_body);

        $emails = $request->sent_to;
        $emailarr = explode(',',$emails);

        \Mail::send('mail.complaint-mail',
            array(
                'ref_no' => $complaintdetails->external_ref_no,
                'date' => $complaintdetails->created_at,
                'name' => $complainantname,
                'subject' => $subject,
                'body' => $mail_body,
            ), function ($message) use ($subject, $emailarr) {
                $message->from('cms@labourdept.gov.lk');
                $message->to($emailarr)->subject($subject);
            }
        );

        \EmailLog::addToLog($complaintdetails->complainant_f_name, $emailarr, $subject, $mail_body);
        // var_dump(Mail::failures());
        // exit;

        DB::commit();
        return redirect()->back()->withInput(['tab'=>'s2']);

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withInput(['tab'=>'s2']);
        }
    }

    public function updateComplainDetail(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $complaintdetail =  RegisterComplaint::find($request->id);
        $complaintdetail->complainant_f_name = $request->complainant_f_name;
        $complaintdetail->complainant_f_name_si = $request->complainant_f_name_si;
        $complaintdetail->complainant_f_name_ta = $request->complainant_f_name_ta;
        $complaintdetail->complainant_l_name = $request->complainant_l_name;
        $complaintdetail->complainant_l_name_si = $request->complainant_l_name_si;
        $complaintdetail->complainant_l_name_ta = $request->complainant_l_name_ta;
        $complaintdetail->complainant_address = $request->complainant_address;
        $complaintdetail->complainant_address_si = $request->complainant_address_si;
        $complaintdetail->complainant_address_ta = $request->complainant_address_ta;
        $complaintdetail->employer_name = $request->employer_name;
        $complaintdetail->employer_name_si = $request->employer_name_si;
        $complaintdetail->employer_name_ta = $request->employer_name_ta;
        $complaintdetail->employer_address = $request->employer_address;
        $complaintdetail->employer_address_si = $request->employer_address_si;
        $complaintdetail->employer_address_ta = $request->employer_address_ta;
        $complaintdetail->save();
        // $id = $data->id;

        // \LogActivity::addToLog('Complaint details record ID '.$id.' updated.');
        DB::commit();
        return redirect()->back()
            ->with('success', 'Record updated successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /***************** START MAIL FUNCTIONALITY **********************/

    // public function print(Request $request)
    // {
    //     $request->validate([
    //         'template_id' => 'required',
    //         'sent_to' => 'required',
    //         'subject' => 'required',
    //         // 'mail_body' => 'required'
    //     ]);

    //     $officername = Auth::user()->name;

    //     $mailhistory = new MailHistory();
    //     $mailhistory->template_id = $request->template_id;
    //     $mailhistory->sent_by = $request->sent_by;
    //     $mailhistory->sent_to = $request->sent_to;
    //     $mailhistory->subject = $request->subject;
    //     $mailhistory->status = 'Print/Email';
    //     $mailhistory->complaint_id = $request->complaint_id;
    //     $mailhistory->pref_lang = $request->pref_lang_mail;
    //     // $mailhistory->heading = $request->mail_heading;

    //     $lang = $request->pref_lang_mail;

    //     $mailtemplatedetails = MailTemplate::where('id', $request->template_id)->first();
    //     $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();


    //     if ($lang == 'SI') {

    //        if($complaintdetails->title == 1) {
    //             $title = 'Mr. ';
    //         } else if($complaintdetails->title == 2) {
    //             $title = 'Miss. ';
    //         } else if($complaintdetails->title == 3) {
    //             $title = 'Mrs. ';
    //         } else if($complaintdetails->title == 4) {
    //             $title = 'Rev. ';
    //         } else {
    //             $title = 'Dr. ';
    //         }

    //         $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]'];

    //         $variableData = [$title,$complaintdetails->employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->employer_address_si,$officername];

    //         $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_sin);

    //         $mail_content = $replace;

    //     } elseif ($lang == "TA") {

    //         if($complaintdetails->title == 1) {
    //             $title = 'Mr. ';
    //         } else if($complaintdetails->title == 2) {
    //             $title = 'Miss. ';
    //         } else if($complaintdetails->title == 3) {
    //             $title = 'Mrs. ';
    //         } else if($complaintdetails->title == 4) {
    //             $title = 'Rev. ';
    //         } else {
    //             $title = 'Dr. ';
    //         }

    //         $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]'];

    //         $variableData = [$title,$complaintdetails->title,$complaintdetails->employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->employer_address_ta,$officername];

    //         $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_tam);

    //         $mail_content = $replace;

    //     } else {

    //         if($complaintdetails->title == 1) {
    //             $title = 'Mr. ';
    //         } else if($complaintdetails->title == 2) {
    //             $title = 'Miss. ';
    //         } else if($complaintdetails->title == 3) {
    //             $title = 'Mrs. ';
    //         } else if($complaintdetails->title == 4) {
    //             $title = 'Rev. ';
    //         } else {
    //             $title = 'Dr. ';
    //         }

    //         $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]'];

    //         $variableData = [$title,$complaintdetails->title,$complaintdetails->employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->employer_address_ta,$officername];

    //         $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_en);

    //         $mail_content = $replace;

    //     }

    //     if ($request->mail_body != '') {
    //         $mail_body = $request->mail_body;
    //     } else {
    //         $mail_body = $mail_content;
    //     }

    //     $mailhistory->mail_body = $mail_body;

    //     $mailhistory->save();

    //     return redirect()->route('print', ['printId' => $mailhistory->id, 'complaintId' => $request->complaint_id])->withInput(['tab'=>'s3'])->with('success', 'Mail sent successfully.');
    // }

    /***************** END MAIL PRINT FUNCTIONALITY *************/

    public function printLetter(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'letter_template_id' => 'required',
        ]);

        $officeid = Auth::user()->office_id;

        $mailtemplatedetails = MailTemplate::where('id', $request->letter_template_id)->first();
        $lang = $request->pref_lang_let;


        if ($lang == 'SI') {
            $mail_subject = $mailtemplatedetails->mail_template_name_sin;
        } elseif ($lang == "TA") {
            $mail_subject = $mailtemplatedetails->mail_template_name_tam;
        } else {
            $mail_subject = $mailtemplatedetails->mail_template_name_en;
        }

        $mailhistory = new MailHistory();
        $mailhistory->template_id = $request->letter_template_id;
        $mailhistory->sent_by = Auth::user()->id;
        $mailhistory->sent_to = $request->letter_complainant_name;
        $mailhistory->subject = $mail_subject;

        // dd($mailhistory->mail_body);
        $mailhistory->status = 'Letter';
        if ($request->categ_nd ==  'ND') {
            $mailhistory->status = 'ND';
        }

        $mailhistory->complaint_id = $request->complaint_id;

        $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();

        $officename = LabourOfficeDivision::where('id', $complaintdetails->current_office_id)->first();

        $officername = User::where('id', $complaintdetails->lo_officer_id)->first();

        $investigationevent = Event::where('complaint_id', $request->complaint_id)->where('status_id', 6)->orWhere('status_id', 19)->latest()->first();

        $complaintcreatedat = $complaintdetails->created_at;
        $comprecdate = $complaintcreatedat->toDateString();

        if($request->letter_template_id == 14 || $request->letter_template_id == 19) {
            if($investigationevent != '') {
                $eventdate = $investigationevent->event_date;
                $time = $investigationevent->start_time;
            } else {
                $eventdate = '[DATE]';
                $time = '[TIME]';
            }
        } else {
            $eventdate = '[DATE]';
            $time = '[TIME]';
        }

        if($request->letter_for == "Employer") {
            if ($lang == 'SI') {

                if($complaintdetails->title == 1) {
                    $title = 'මහතා';
                } else if($complaintdetails->title == 2) {
                    $title = 'මෙනවිය';
                } else if($complaintdetails->title == 3) {
                    $title = 'මහත්මිය';
                } else if($complaintdetails->title == 4) {
                    $title = 'ගරු';
                } else {
                    $title = 'ආචාර්ය';
                }

                if($officername == "") {
                    $officerLo = "[LONAME]";
                } else {
                    $officerLo = $officername->name_si;
                }

                if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = "[TERMINATIONDATE]";
                } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = "[TERMINATIONDATE]";
                } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = $complaintdetails->terminate_date;
                } else {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = $complaintdetails->terminate_date;
                }

                $complainantname = $complaintdetails->complainant_f_name_si.' '.$complaintdetails->complainant_l_name_si;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

                $variableData = [$title,$complaintdetails->current_employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_si,$officerLo,$officename->office_name_sin,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address_si,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

                $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_sin);

                $mail_content = $replace;

            } elseif ($lang == "TA") {

                if($complaintdetails->title == 1) {
                    $title = 'திரு ';
                } else if($complaintdetails->title == 2) {
                    $title = 'செல்வி ';
                } else if($complaintdetails->title == 3) {
                    $title = 'திருமதி ';
                } else if($complaintdetails->title == 4) {
                    $title = 'கௌரவ ';
                } else {
                    $title = 'டாக்டர் ';
                }

                if($officername == "") {
                    $officerLo = "[LONAME]";
                } else {
                    $officerLo = $officername->name_ta;
                }

                if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = "[TERMINATIONDATE]";
                } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = "[TERMINATIONDATE]";
                } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = $complaintdetails->terminate_date;
                } else {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = $complaintdetails->terminate_date;
                }

                $complainantname = $complaintdetails->complainant_f_name_ta.' '.$complaintdetails->complainant_l_name_ta;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

                $variableData = [$title,$complaintdetails->current_employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_ta,$officerLo,$officename->office_name_tam,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address_ta,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

                $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_tam);

                $mail_content = $replace;

            } else {

                if($complaintdetails->title == 1) {
                    $title = 'Mr. ';
                } else if($complaintdetails->title == 2) {
                    $title = 'Miss. ';
                } else if($complaintdetails->title == 3) {
                    $title = 'Mrs. ';
                } else if($complaintdetails->title == 4) {
                    $title = 'Rev. ';
                } else {
                    $title = 'Dr. ';
                }

                if($officername == "") {
                    $officerLo = "[LONAME]";
                } else {
                    $officerLo = $officername->name;
                }

                if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = "[TERMINATIONDATE]";
                } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = "[TERMINATIONDATE]";
                } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = $complaintdetails->terminate_date;
                } else {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = $complaintdetails->terminate_date;
                }

                $complainantname = $complaintdetails->complainant_f_name.' '.$complaintdetails->complainant_l_name;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','OFFICENAME','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

                $variableData = [$title,$complaintdetails->current_employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address,$officerLo,$officename->office_name_en,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

                $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_en);

                $mail_content = $replace;
            }
        } else {
            if ($lang == 'SI') {

                if($complaintdetails->title == 1) {
                    $title = 'මහතා';
                } else if($complaintdetails->title == 2) {
                    $title = 'මෙනවිය';
                } else if($complaintdetails->title == 3) {
                    $title = 'මහත්මිය';
                } else if($complaintdetails->title == 4) {
                    $title = 'ගරු';
                } else {
                    $title = 'ආචාර්ය';
                }

                if($officername == "") {
                    $officerLo = "[LONAME]";
                } else {
                    $officerLo = $officername->name_si;
                }

                if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = "[TERMINATIONDATE]";
                } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = "[TERMINATIONDATE]";
                } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = $complaintdetails->terminate_date;
                } else {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = $complaintdetails->terminate_date;
                }

                $complainantname = $complaintdetails->complainant_f_name_si.' '.$complaintdetails->complainant_l_name_si;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

                $variableData = [$title,$complaintdetails->current_employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_si,$officerLo,$officename->office_name_sin,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address_si,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

                $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_sin);

                $mail_content = $replace;
            } elseif ($lang == "TA") {

                if($complaintdetails->title == 1) {
                    $title = 'திரு ';
                } else if($complaintdetails->title == 2) {
                    $title = 'செல்வி ';
                } else if($complaintdetails->title == 3) {
                    $title = 'திருமதி ';
                } else if($complaintdetails->title == 4) {
                    $title = 'கௌரவ ';
                } else {
                    $title = 'டாக்டர் ';
                }

                if($officername == "") {
                    $officerLo = "[LONAME]";
                } else {
                    $officerLo = $officername->name_ta;
                }

                if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = "[TERMINATIONDATE]";
                } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = "[TERMINATIONDATE]";
                } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = $complaintdetails->terminate_date;
                } else {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = $complaintdetails->terminate_date;
                }

                $complainantname = $complaintdetails->complainant_f_name_ta.' '.$complaintdetails->complainant_l_name_ta;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

                $variableData = [$title,$complaintdetails->current_employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_ta,$officerLo,$officename->office_name_tam,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address_ta,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

                $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_tam);

                $mail_content = $replace;
            } else {

                if($complaintdetails->title == 1) {
                    $title = 'Mr. ';
                } else if($complaintdetails->title == 2) {
                    $title = 'Miss. ';
                } else if($complaintdetails->title == 3) {
                    $title = 'Mrs. ';
                } else if($complaintdetails->title == 4) {
                    $title = 'Rev. ';
                } else {
                    $title = 'Dr. ';
                }

                if($officername == "") {
                    $officerLo = "[LONAME]";
                } else {
                    $officerLo = $officername->name;
                }

                if($complaintdetails->join_date == "" || $complaintdetails->terminate_date == "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = "[TERMINATIONDATE]";
                } else if ($complaintdetails->join_date != "" || $complaintdetails->terminate_date == "") {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = "[TERMINATIONDATE]";
                } else if($complaintdetails->join_date == "" || $complaintdetails->terminate_date != "") {
                    $joineddate = "[JOINEDDATE]";
                    $terminationdate = $complaintdetails->terminate_date;
                } else {
                    $joineddate = $complaintdetails->join_date;
                    $terminationdate = $complaintdetails->terminate_date;
                }

                $complainantname = $complaintdetails->complainant_f_name.' '.$complaintdetails->complainant_l_name;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[LONAME]','[OFFICENAME]','[EMPDESIGNATION]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]','[JOINEDDATE]','[TERMINATIONDATE]','[DATE]','[TIME]','[COMPLAINTRECEIVEDDATE]'];

                $variableData = [$title,$complaintdetails->current_employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address,$officerLo,$officename->office_name_en,$complaintdetails->designation,$complainantname,$complaintdetails->complainant_address,$joineddate,$terminationdate,$eventdate,$time,$comprecdate];

                $replace = str_ireplace($variables, $variableData, $mailtemplatedetails->body_content_en);

                $mail_content = $mailtemplatedetails->body_content_en;
            }
        }

        if ($request->letter_body != '') {
            $mail_body = $request->letter_body;
        } else {
            $mail_body = $mail_content;
        }

        $mailhistory->mail_body = $mail_body;
        $mailhistory->recipient = $request->letter_for;
        $mailhistory->pref_lang = $request->pref_lang_let;
        $mailhistory->address = $request->letter_complainant_address;
        // $mailhistory->heading = $request->letter_heading;
        $mailhistory->save();

        DB::commit();
        return redirect()->route('print', ['printId' => $mailhistory->id, 'complaintId' => $request->complaint_id, 'officeid' => $officeid])->withInput(['tab'=>'s1'])->with('success', 'Letter sent successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }


    public function printview(Request $request)
    {
        $PrintId = $request->printId;
        $complaintId = $request->complaintId;
        $officeId = $request->officeid;

        $labourofficedetails = LabourOfficeDivision::where('id', $officeId)->first();

        $complainantdetails = RegisterComplaint::where('id', $complaintId)->first();

        $mailhistories = MailHistory::where('id', $PrintId)->get();

        // $date = Carbon::now();
        $date = Carbon::now()->format('Y-m');

        // $todayDate = $date->toDateString();
        $todayDate = $date;
        // dd($officeId);

        return view('adminpanel.complaint.print',['mailhistories' => $mailhistories, 'labourofficedetails' => $labourofficedetails, 'complainantdetails' => $complainantdetails, 'todayDate' => $todayDate]);
    }

    public function getEvents(Request $request)
    {
        $events = Event::where('complaint_id', '>', 1)->get();

        //->pluck("event_icon", "id","event_title","event_date","start_time","end_time","description","event_color");
        return response()->json($events);
    }

    public function getSentMail($id)
    {
        $sentmails = MailHistory::with('complaintdetails','mailtemplatedetails')->find($id);
        return json_encode($sentmails);
    }

    public function getRecipient($id)
    {
        $recipients = RegisterComplaint::select('complainant_email','complainant_f_name','complainant_f_name_si','complainant_f_name_ta')->where('id',$id)->get();
        // return json_encode($recipients);
        return response()->json($recipients);
    }

    public function getSentEMail($id)
    {
        $sentmails = MailHistory::with('complaintdetails','mailtemplatedetails')->find($id);
        return json_encode($sentmails);
    }

    public function checkEventsDuplicate(Request $request)
    {
        // echo $request->startTime;
        //   exit();
        // \DB::enableQueryLog();
        $events = Event::where("event_date", $request->eventDate)
                       ->where('officer_id', $request->loID)
                        ->where('start_time', '>=', $request->startTime)
                        ->where('end_time', '<=', $request->endTime)
                        ->get();
                        // $events = \DB::getQueryLog();
                        // print_r($events);
                        // exit();

        return response()->json($events);
    }

    public function calculation($id)
    {
        $complainID = decrypt($id);
        $data = RegisterComplaint::find($complainID);

        $gratuityDetails = GratuityDetails::where('complaint_id', $complainID)->first();

        $minWageMainDetails = MinimumWageMain::where('complaint_id', $complainID)->first();

        if($minWageMainDetails != "") {
            $minWageDetails = MinimumWageDetail::where('minimum_wage_main_id', $minWageMainDetails->id)->get();
        } else {
            $minWageDetails = "";
        }


        return view('adminpanel.complaint.gratuity', ['data' => $data, 'gratuityDetails' => $gratuityDetails, 'minWageMainDetails' => $minWageMainDetails, 'minWageDetails' => $minWageDetails]);
    }

    public function gratuitycalculation(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'wage_type' => 'required',
            'paid_status' => 'required',
            'gratuity_amount' => 'required',
            'surcharge' => 'required',
            'tot_gratuity_amount' => 'required',
        ]);

        // dd($request->complain_id);

        $complaint =  GratuityDetails::where('complaint_id',$request->complaint_id)->first();
        
        if($complaint == "") {
            $gratuity = new GratuityDetails();
            
            $gratuity->complaint_id = $request->complain_id;
            $gratuity->wage_type = $request->wage_type;
            $gratuity->paid_status = $request->paid_status;
            $gratuity->gratuity_paid_date = $request->gratuity_paid_date;
            $gratuity->gratuity_paid_amount = floatval(preg_replace("/[^-0-9\.]/","",$request->gratuity_paid_amount));

            if($request->last_salary != "") {

                $gratuity->received_sal = floatval(preg_replace("/[^-0-9\.]/","",$request->last_salary));

            } elseif ($request->daily_salary != "") {

                $gratuity->received_sal = floatval(preg_replace("/[^-0-9\.]/","",$request->daily_salary));

            } else {

                $gratuity->received_sal = floatval(preg_replace("/[^-0-9\.]/","",$request->last_three_mon_salary));

            }
            
            $gratuity->working_days = $request->last_three_mon_work_days;
            $gratuity->gratuity_amount = floatval(preg_replace("/[^-0-9\.]/","",$request->gratuity_amount));
            $gratuity->surcharge = floatval(preg_replace("/[^-0-9\.]/","",$request->surcharge));
            $gratuity->total_gratuity = floatval(preg_replace("/[^-0-9\.]/","",$request->tot_gratuity_amount));
            $gratuity->gratuity_due_date = $request->gratuity_due_date;
            $gratuity->working_years = $request->working_years;
            $gratuity->surcharge_percentage = $request->surcharge_percentage;

            // dd($gratuity);
            $gratuity->save();

        } else {
            
            $complaint->complaint_id = $request->complain_id;
            $complaint->wage_type = $request->wage_type;
            $complaint->paid_status = $request->paid_status;
            $complaint->gratuity_paid_date = $request->gratuity_paid_date;
            $complaint->gratuity_paid_amount = floatval(preg_replace("/[^-0-9\.]/","",$request->gratuity_paid_amount));

            if($request->last_salary != "") {

                $complaint->received_sal = floatval(preg_replace("/[^-0-9\.]/","",$request->last_salary));

            } elseif ($request->daily_salary != "") {

                $complaint->received_sal = floatval(preg_replace("/[^-0-9\.]/","",$request->daily_salary));

            } else {

                $complaint->received_sal = floatval(preg_replace("/[^-0-9\.]/","",$request->last_three_mon_salary));

            }

            $complaint->working_days = $request->last_three_mon_work_days;
            $complaint->gratuity_amount = floatval(preg_replace("/[^-0-9\.]/","",$request->gratuity_amount));
            $complaint->surcharge = floatval(preg_replace("/[^-0-9\.]/","",$request->surcharge));
            $complaint->total_gratuity = floatval(preg_replace("/[^-0-9\.]/","",$request->tot_gratuity_amount));
            $complaint->gratuity_due_date = $request->gratuity_due_date;
            
            $complaint->working_years = $request->working_years;
            $complaint->surcharge_percentage = $request->surcharge_percentage;
            $complaint->update();
            
        }

            // \LogActivity::addToLog('New File record ID '.$id.' uploaded to complaint record ID '.$request->complaint_id.'.');

        DB::commit();
        return redirect()->route('mail', ['id' => encrypt($request->complain_id)])
            ->with('success', 'Gratuity saved successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function minWagescalculation(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'wage_type_min_wages' => 'required',
            'month_days_count' => 'required',
            'monthly_daily_salary' => 'required',
            'deficit_budget_allowance' => 'required',
        ]);

        // dd($request->deficit_budget_allowance);

        $minWage = new MinimumWageMain();

        $minWage->complaint_id = $request->complain_id;
        $minWage->wage_type = $request->wage_type_min_wages;
        $minWage->tot_months_days_underpaid = $request->total_dates;
        $minWage->tot_dificit_budget_allowance = $request->total_dificit_budget_allowance;

        $minWage->save();

        // $minWageDetails = new MinimumWageDetail();

        // $minWageDetails->minimum_wage_main_id = $minWage->id;
        // $minWageDetails->months_days_underpaid = $request->month_days_count;
        // $minWageDetails->salary_underpaid = $request->monthly_daily_salary;
        // $minWageDetails->dificit_budget_allowance = $request->deficit_budget_allowance;

        // $minWageDetails->save();

        if($request->deficit_budget_allowance != "" && $request->deficit_budget_allowance != null) {
            $count = count($request->deficit_budget_allowance);

            for ($i = 0; $i < $count; $i++) {

                $minWageDetails = new MinimumWageDetail();
                $minWageDetails->minimum_wage_main_id = $minWage->id;
                $minWageDetails->months_days_underpaid = $request->month_days_count[$i];
                $minWageDetails->salary_underpaid = $request->monthly_daily_salary[$i];
                $minWageDetails->dificit_budget_allowance = $request->deficit_budget_allowance[$i];
                $minWageDetails->save();
            }
        }

            // \LogActivity::addToLog('New File record ID '.$id.' uploaded to complaint record ID '.$request->complaint_id.'.');

        DB::commit();
        return redirect()->route('action-pending-list')
            ->with('success', 'Minimum Wage saved successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function deleteminwage($id)
    {
        $minWages = MinimumWageMain::find($id);
        $minWages->delete();

        $minWagesDetail = MinimumWageDetail::where('minimum_wage_main_id', $id);
        $minWagesDetail->delete();


        return redirect()->back()->with('success', 'Minimum wages deleted');
    }

    public function saveNd(Request $request)
    {
        DB::beginTransaction();
        try {
           $request->validated([
                'nd_template_id' => 'required',
            ]);

            $mailtemplatedetails = MailTemplate::where('id', $request->nd_template_id)->first();
            $lang = $request->pref_lang_nd;


            if ($lang == 'SI') {
                $mail_subject = $mailtemplatedetails->mail_template_name_sin;
            } elseif ($lang == "TA") {
                $mail_subject = $mailtemplatedetails->mail_template_name_tam;
            } else {
                $mail_subject = $mailtemplatedetails->mail_template_name_en;
            }

            $mailhistory = new MailHistory();
            $mailhistory->template_id = $request->nd_template_id;
            $mailhistory->sent_by = Auth::user()->id;
            $mailhistory->sent_to = $request->nd_complainant_name;
            $mailhistory->subject = $mail_subject;
            $mailhistory->mail_body = $request->nd_body;
            $mailhistory->complaint_id = $request->complaint_id;
            $mailhistory->recipient = $request->nd_for;
            $mailhistory->pref_lang = $lang;
            $mailhistory->address = $request->nd_complainant_address;
            $mailhistory->heading = $request->nd_heading;
            $mailhistory->status ="ND";
            $mailhistory->save();

        DB::commit();

         return redirect()->back()
           ->with('success', 'Mail history created successfully.');

        }catch(Exception $ex){
            DB::rollBack();
        }
    }

    public function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' cents ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            100000             => 'lakh',
            10000000          => 'crore'
        );
    
        if (!is_numeric($number)) {
            return false;
        }
    
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
    
        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }
    
        $string = $fraction = null;
    
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
    
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 100000:
                $thousands   = ((int) ($number / 1000));
                $remainder = $number % 1000;
    
                $thousands = $this->convert_number_to_words($thousands);
    
                $string .= $thousands . ' ' . $dictionary[1000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 10000000:
                $lakhs   = ((int) ($number / 100000));
                $remainder = $number % 100000;
    
                $lakhs = $this->convert_number_to_words($lakhs);
    
                $string = $lakhs . ' ' . $dictionary[100000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 1000000000:
                $crores   = ((int) ($number / 10000000));
                $remainder = $number % 10000000;
    
                $crores = $this->convert_number_to_words($crores);
    
                $string = $crores . ' ' . $dictionary[10000000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }
    
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
    
        return $string;
    }
}
