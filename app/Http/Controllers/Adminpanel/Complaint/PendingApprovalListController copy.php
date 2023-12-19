<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use DataTables;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\ComplaintHistory;
use App\Models\Complain_Category;
use App\Models\ComplaintDocument;
use App\Models\EstablishmentType;
use App\Models\RegisterComplaint;
use App\Models\UnionOfficerDetail;
use App\Http\Controllers\Controller;
use App\Models\LabourOfficeDivision;
use App\Models\User;
use App\Models\MailTemplate;
use App\Library\MobitelSms;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class PendingApprovalListController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:pending-approval-list|pending-approval-status-history|pending-approval-action|pending-approval-view', ['only' => ['list']]);
        $this->middleware('permission:pending-approval-status-history', ['only' => ['complaintStatus']]);
        $this->middleware('permission:pending-approval-action', ['only' => ['complaintAction', 'forward']]);
        $this->middleware('permission:pending-approval-view', ['only' => ['view']]);
    }

    public function list(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $approveCount = RegisterComplaint::where('complaint_status','Approve')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Reject')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('action_type','Pending_approve')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('id','ref_no','external_ref_no','complainant_f_name','created_at','complainant_identify_no','complaint_status','updated_at')->where('action_type','Pending_approve')->where('current_office_id',$office_id);
        //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                })
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                                        ->count();
                    $edit_url = "";
                    $status_url = url('/pending-approval-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-approval-action/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-mail-forward "></i></a>';
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.pendingApprovalStatus', ['statusCount' => $statusCount])
                //->addColumn('action', 'adminpanel.complaint.pendingApprovalAction')
                ->addColumn('view', function ($row) {
                    $view_url = url('/pending-approval-view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.pendingApprovalList', ['pendingCount' => $pendingCount, 'rejectCount' => $rejectCount, 'approveCount' => $approveCount ]);
    }

    public function view($id)
    {
        $complainID = decrypt($id);
        $data = RegisterComplaint::with('provinces', 'districts', 'establishments', 'labouroffices')->find($complainID);

        $complaintdocuments = ComplaintDocument::where('ref_no', $complainID)->get();

        $complaintstatusdetails = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'desc')->get();

        return view('adminpanel.complaint.view', ['data' => $data, 'complaintdocuments' => $complaintdocuments, 'complaintstatusdetails' => $complaintstatusdetails]);
    }

    public function complaintStatus($id)
    {
        $complainID = decrypt($id);
        $data = RegisterComplaint::find($complainID);
        $complaintstatusdetails = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'desc')->get();

        return view('adminpanel.complaint.complaint_status_history', ['complaintstatusdetails' => $complaintstatusdetails, 'data' => $data]);
    }

    public function complaintAction($id)
    {
        $complainID = decrypt($id);
        $office_id = Auth::user()->office_id;
        $data = RegisterComplaint::find($complainID);
        $office = LabourOfficeDivision::where('status', 'Y')
        ->where('is_delete', '0')
        ->where('id','!=' ,$office_id)
        ->get();
        $history = ComplaintHistory::where('status', 'Request_approve_close')
        ->orWhere('status', 'Request_approve_temp_close')
        ->orWhere('status', 'Request_assign_lo')
        ->orWhere('status', 'Request_approve_forward')
        ->where('complaint_id', $complainID)
        ->orderBy('id', 'desc')
        ->get();

        // dd($history);

        $requestAssignLO = User::where('id', $history[0]->assigned_lo_id)->first();

        // dd($complainID);

        return view('adminpanel.complaint.action_approval', ['data' => $data,'labourOffice' => $office, 'history' => $history, 'requestAssignLO' => $requestAssignLO]);
    }

    public function forward(Request $request)
    {
        $request->validate([
            'action_type' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);


        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if(!empty($labour_office)){
            $sent_from_office_code = $labour_office->office_code;
        }else{
            $sent_from_office_code = NULL;
        }

        $labour_office = LabourOfficeDivision::where('id', $request->labour_office_id)->first();

        $remark = ComplaintHistory::where('complaint_id', $complaint->id)->orderBy('id', 'DESC')->first();

        if(!empty($labour_office)){
            $sent_to_office_code = $labour_office->office_code;
        }else{
            $sent_to_office_code = NULL;
        }

        if($complaint->complaint_status == 'Request_assign_lo') {
            if($request->action_type == 'Approve') {
                $status = 'Approved_assign_lo';
                if($complaint->pref_lang == "SI") {
                    $status_des = "විමර්ශනය ආරම්භ කළා";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "விசாரணை ஆரம்பம்";
                } else {
                    $status_des = "Investigation Started";
                }
                $action_type = 'Ongoing';
                $assigned_lo_officer = $request->labour_officer_id;
                $sent_to_office_code = NULL;
                $insert['assigned_lo_id'] = $assigned_lo_officer;
                $complaint->lo_officer_id = $assigned_lo_officer;
            } else {
                $status = 'Rejected_assigned_lo';
                if($complaint->pref_lang == "SI") {
                    $status_des = "ඔබගේ පැමිණිල්ල $sent_from_office_code කාර්යාලයට යොමු කළා. වැඩිදුර විමර්ශනය සඳහා පෙළගස්වා තිබේ";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "உங்கள் முறைப்பாடு $sent_from_office_code அலுவலகத்திற்கு அனுப்பப்பட்டுள்ளதோடு, மேலதிக விசாரணைக்காக வரிசைப்படுத்தப்பட்டுள்ளது";
                } else {
                    $status_des = "Your compline has forward to $sent_from_office_code office, Queued for futher investigation";
                }
                $action_type = 'Pending';
                $assigned_lo_officer = NULL;
            }

        } else {

            if($request->action_type == 'Approve' && $complaint->complaint_status == 'Request_approve_temp_close'){
                $status = 'Approved_temp_close';
                if($complaint->pref_lang == "SI") {
                    $status_des = "අනුමැතිය ලැබුණා $sent_from_office_code";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "$sent_from_office_code இற்கு ஒப்புதல் பெறப்பட்டது";
                } else {
                    $status_des = "approval received for $sent_from_office_code";
                }
                $action_type = 'Pending';
            } else if($request->action_type == 'Approve' && $complaint->complaint_status == 'Request_approve_close'){
                $status = 'Closed';
                if($complaint->pref_lang == "SI") {
                    $status_des = "පැමිණිල්ල වසා ඇත";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "$sent_from_office_code முறைப்பாடு மூடப்பட்டுள்ளது";
                } else {
                    $status_des = "Complaint closed";
                }
                $action_type = 'Closed';
            } else if($request->action_type == 'Approve' && $complaint->complaint_status == 'Request_approve_forward'){
                $status = 'Approved_forward';
                if($complaint->pref_lang == "SI") {
                    $status_des = "අනුමැතිය ලැබුණා $sent_from_office_code";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "$sent_from_office_code இற்கு ஒப்புதல் பெறப்பட்டது";
                } else {
                    $status_des = "approval received for $sent_from_office_code";
                }
                $action_type = 'Pending';
            } else if($request->action_type == 'Reject' && $complaint->complaint_status == 'Request_approve_temp_close'){
                $status = 'Rejected_temp_close';
                if($complaint->pref_lang == "SI") {
                    $status_des = "ඔබගේ පැමිණිල්ල $sent_from_office_code කාර්යාලයට යොමු කළා. වැඩිදුර විමර්ශනය සඳහා පෙළගස්වා තිබේ";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "உங்கள் முறைப்பாடு $sent_from_office_code அலுவலகத்திற்கு அனுப்பப்பட்டுள்ளதோடு, மேலதிக விசாரணைக்காக வரிசைப்படுத்தப்பட்டுள்ளது";
                } else {
                    $status_des = "Your compline has forward to $sent_from_office_code office, Queued for futher investigation";
                }
                $action_type = 'Pending';
            } else if($request->action_type == 'Reject' && $complaint->complaint_status == 'Request_approve_close') {
                $status = 'Rejected_close';
                if($complaint->pref_lang == "SI") {
                    $status_des = "ඔබගේ පැමිණිල්ල $sent_from_office_code කාර්යාලයට යොමු කළා. වැඩිදුර විමර්ශනය සඳහා පෙළගස්වා තිබේ";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "உங்கள் முறைப்பாடு $sent_from_office_code அலுவலகத்திற்கு அனுப்பப்பட்டுள்ளதோடு, மேலதிக விசாரணைக்காக வரிசைப்படுத்தப்பட்டுள்ளது";
                } else {
                    $status_des = "Your compline has forward to $sent_from_office_code office, Queued for futher investigation";
                }
                $action_type = 'Pending';
            } else if($request->action_type == 'Reject' && $complaint->complaint_status == 'Request_approve_forward'){
                $status = 'Rejected_forward';
                if($complaint->pref_lang == "SI") {
                    $status_des = "ඔබගේ පැමිණිල්ල $sent_from_office_code කාර්යාලයට යොමු කළා. වැඩිදුර විමර්ශනය සඳහා පෙළගස්වා තිබේ";
                } else if($complaint->pref_lang == "TA") {
                    $status_des = "உங்கள் முறைப்பாடு $sent_from_office_code அலுவலகத்திற்கு அனுப்பப்பட்டுள்ளதோடு, மேலதிக விசாரணைக்காக வரிசைப்படுத்தப்பட்டுள்ளது";
                } else {
                    $status_des = "Your compline has forward to $sent_from_office_code office, Queued for futher investigation";
                }
                $action_type = 'Pending';
            }
            //$assigned_lo_officer = NULL;
        }

        $complaint->complaint_status = $status;
        $complaint->action_type = $action_type;
        $complaint->current_office_id = $request->current_office;
        $complaint->save();

        if($status == "Closed") {
            if($complaint->complainant_email != ''){

                $mailitem = MailTemplate::where('status', 'Y')
                        ->where('is_delete', 0)
                        ->where('id', 2)
                        ->get();
                //dd();exit();
                //\App::setLocale($regdata[0]->pref_lang);

                if($complaint->pref_lang == 'EN'){
                    $e_sub = $mailitem[0]->mail_template_name_en;
                    // $e_body = $mailitem[0]->body_content_en;
                    $e_name = $mailitem[0]->mail_template_name_en;

                    if($complaint->title == 1) {
                        $title = 'Mr. ';
                    } else if($complaint->title == 2) {
                        $title = 'Miss. ';
                    } else if($complaint->title == 3) {
                        $title = 'Mrs. ';
                    } else if($complaint->title == 4) {
                        $title = 'Rev. ';
                    } else {
                        $title = 'Dr. ';
                    }

                    $complainantname = $complaint->complainant_f_name;

                    $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$title,$complaint->current_employer_name,$complaint->employer_address,$complaint->employer_name,$complaint->external_ref_no,$complaint->current_employer_address,$labour_office->office_name_en,$remark->remark,$complaint->complainant_f_name,$complaint->complainant_address];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

                    // $email_body = 'Dear'.' '.$complainantname.','.$e_body;

                } else if($complaint->pref_lang == 'SI'){
                    $e_sub = $mailitem[0]->mail_template_name_sin;
                    // $e_body = $mailitem[0]->body_content_sin;
                    $e_name = $mailitem[0]->mail_template_name_sin;

                    if($complaint->title == 1) {
                        $title = 'මහතා';
                    } else if($complaint->title == 2) {
                        $title = 'මෙනවිය';
                    } else if($complaint->title == 3) {
                        $title = 'මහත්මිය';
                    } else if($complaint->title == 4) {
                        $title = 'ගරු';
                    } else {
                        $title = 'ආචාර්ය';
                    }

                    $complainantname = $complaint->complainant_f_name_si;

                    $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$title,$complaint->current_employer_name_si,$complaint->employer_address_si,$complaint->employer_name_si,$complaint->external_ref_no,$complaint->current_employer_address_si,$labour_office->office_name_si,$remark->remark,$complaint->complainant_f_name_si,$complaint->complainant_address_si];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

                    // $email_body = 'Dear'.' '.$complainantname.','.$e_body;

                } else if($complaint->pref_lang == 'TA'){
                    $e_sub = $mailitem[0]->mail_template_name_tam;
                    // $e_body = $mailitem[0]->body_content_tam;
                    $e_name = $mailitem[0]->mail_template_name_tam;

                    if($complaint->title == 1) {
                        $title = 'திரு ';
                    } else if($complaint->title == 2) {
                        $title = 'செல்வி ';
                    } else if($complaint->title == 3) {
                        $title = 'திருமதி ';
                    } else if($complaint->title == 4) {
                        $title = 'கௌரவ ';
                    } else {
                        $title = 'டாக்டர் ';
                    }

                    $complainantname = $complaint->complainant_f_name_ta;

                    $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                    $variableData = [$title,$complaint->current_employer_name_ta,$complaint->employer_address_ta,$complaint->employer_name_ta,$complaint->external_ref_no,$complaint->current_employer_address_ta,$labour_office->office_name_ta,$remark->remark,$complaint->complainant_f_name_ta,$complaint->complainant_address_ta];

                    $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

                    // $email_body = 'Dear'.' '.$complainantname.','.$e_body;
                }

                $labourofficedetails = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

                $date = Carbon::now();

                $todayDate = $date->toDateString();

                \Mail::send('mail.complaint-mail',
                    array(
                    'complaintdetails' => $complaint,
                    'subject' => $e_sub,
                    'body' => $e_body,
                    'labourofficedetails' => $labourofficedetails,
                    'todayDate' => $todayDate
                    ), function($message) use ($e_name, $complaint)
                {
                    $message->from('karshan@tekgeeks.net');
                    $message->to($complaint->complainant_email)->subject($e_name);
                });

                \EmailLog::addToLog($complaint->complainant_f_name, $complaint->complainant_email, $e_sub, $e_body);
            }

            if($complaint->complainant_mobile != ''){

                $smsitem = SmsTemplate::where('status', 'Y')
                    ->where('is_delete', 0)
                    ->where('id', 2)
                    ->get();

                if($complaint->pref_lang == 'EN'){
                    $s_sub = $smsitem[0]->sms_template_name_en;
                    // $s_body = $smsitem[0]->body_content_en;

                    $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[REMARK]','[COMPLAINANTNAME]'];

                    $variableData = [$labour_office->office_name_en,$complaint->external_ref_no,$remark->remark,$complaint->complainant_f_name];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

                    $sms_body = $s_body;

                } else if($complaint->pref_lang == 'SI'){
                    $s_sub = $smsitem[0]->sms_template_name_sin;
                    // $s_body = $smsitem[0]->body_content_sin;

                    $variables = ['[OFFICENAME]','REFERENCENUMBER','[REMARK]','[COMPLAINANTNAME]'];

                    $variableData = [$labour_office->office_name_sin,$complaint->external_ref_no,$remark->remark,$complaint->complainant_f_name_si];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

                    $sms_body = $s_body;

                } else if($complaint->pref_lang == 'TA'){
                    $s_sub = $smsitem[0]->sms_template_name_tam;
                    // $s_body = $smsitem[0]->body_content_tam;

                    $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[REMARK]','[COMPLAINANTNAME]'];

                    $variableData = [$labour_office->office_name_sin,$complaint->external_ref_no,$remark->remark,$complaint->complainant_f_name_ta];

                    $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                    $sms_body = $s_body;
                }

                // $session= MobitelSms::createSession('','esmsusr_uqt','2L@boUr$m$','');
                // MobitelSms::sendMessagesMultiLang($session,'Labour Dept','Dear '.$complaint->complainant_f_name.','.$s_body,array($complaint->complainant_mobile),0);
                // MobitelSms::closeSession($session);

                $mobitelSms = new MobitelSms();
                $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
                $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($complaint->complainant_mobile),0);
                $mobitelSms->closeSession($session);

                \SmsLog::addToLog($complaint->complainant_f_name, $complaint->complainant_mobile, $sms_body);
            }
        }


        \LogActivity::addToLog('Complaint record ID '.$request->complaint_id.' status '.$status.' updated.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = $status;

        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;

        // $insert['sent_from_office'] = Auth::user()->office_id;
        // $insert['sent_from_office_code'] = 'WZI';

        $insert['sent_to_office'] = $request->current_office;
        $insert['sent_to_office_code'] = $sent_to_office_code;
        $insert['action_type'] = $action_type;
        $insert['remark'] = $request->remark;
        $insert['status_des'] = $status_des;

        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['forward_type_id'] = 0;
        $insert['complaint_status_id'] = 0;

        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record ID '.$id.' added to complaint record ID '.$request->complaint_id.' with status '.$status.'.');

        if($request->action_type == 'Approve'){
            $forward = 'approved';
        } else {
            $forward = 'rejected';
        }

        return redirect()->route('pending-approval-list')
            ->with('success', "Complaint $forward successfully.");

    }

    public function approvedList(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $approveCount = RegisterComplaint::where('complaint_status','Approve')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Reject')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('complaint_status','Pending Approval')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('*')->where('complaint_status','Approve')->where('current_office_id',$office_id)->orderBy('id', 'DESC');
        //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                                        ->count();
                    $edit_url = "";
                    $status_url = url('/pending-approval-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-approval-action/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-mail-forward "></i></a>';
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.pendingApprovalStatus', ['statusCount' => $statusCount])
                //->addColumn('action', 'adminpanel.complaint.pendingApprovalAction')
                ->addColumn('view', function ($row) {
                    $view_url = url('/approval-view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.approvedList', ['approveCount' => $approveCount, 'rejectCount' => $rejectCount, 'pendingCount' => $pendingCount ]);
    }

    // public function changeAction($id)
    // {
    //     $complainID = decrypt($id);
    //     $office_id = Auth::user()->office_id;
    //     $data = RegisterComplaint::find($complainID);
    //     $office = LabourOfficeDivision::where('status', 'Y')
    //     ->where('is_delete', '0')
    //     ->where('id','!=' ,$office_id)
    //     ->get();
    //     $history = ComplaintHistory::where('status', 'Pending Approval')
    //     ->where('complaint_id', $complainID)
    //     ->orderBy('id', 'desc')
    //     ->get();

    //     return view('adminpanel.complaint.action_approval_change', ['data' => $data,'labourOffice' => $office, 'history' => $history]);
    // }

    public function rejectedList(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $approveCount = RegisterComplaint::where('complaint_status','Approve')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Reject')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('complaint_status','Request_approve_temp_close')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('*')->where('complaint_status','Reject')->where('current_office_id',$office_id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                                        ->count();
                    $edit_url = "";
                    $status_url = url('/pending-approval-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-approval-action/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-mail-forward "></i></a>';
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.pendingApprovalStatus', ['statusCount' => $statusCount])
                //->addColumn('action', 'adminpanel.complaint.pendingApprovalAction')
                ->addColumn('view', function ($row) {
                    $view_url = url('/approval-view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.rejectedList', ['approveCount' => $approveCount, 'rejectCount' => $rejectCount, 'pendingCount' => $pendingCount ]);
    }

}