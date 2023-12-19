<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use DataTables;
use Illuminate\Http\Request;
use App\Models\ComplaintHistory;
use App\Models\complaintStatus;
use App\Models\ComplaintDocument;
use App\Models\ComplaintRemark;
use App\Models\RegisterComplaint;
use App\Models\ForwardType;
use App\Http\Controllers\Controller;
use App\Models\LabourOfficeDivision;
use App\Models\User;
use App\Models\MailTemplate;
use App\Models\SmsTemplate;
use App\Library\MobitelSms;
use Illuminate\Support\Facades\Auth;

class PendingRecoveryListController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:recovery-pending-list', ['only' => ['pendinglist']]);
        $this->middleware('permission:pending-recovery-list|pending-recovery-status-history|pending-recovery-action', ['only' => ['list']]);
        $this->middleware('permission:pending-recovery-status-history', ['only' => ['complaintStatus']]);
        $this->middleware('permission:pending-recovery-action', ['only' => ['complaintAction', 'forward']]);

    }

    public function list(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $approveCount = RegisterComplaint::where('complaint_status','Recovered')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Rejected')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('action_type','Pending_recovery')
                            ->where('current_office_id',$office_id)
                            ->count();

        $pendingApprovalCount = RegisterComplaint::where('action_type','Pending_approve')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('*')->where('action_type','Pending_recovery')->where('current_office_id',$office_id);
            // var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                })
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                                        ->count();
                    $edit_url = "";
                    $status_url = url('/pending-recovery-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-recovery-action/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-mail-forward "></i></a>';
                    return $btn;
                })
                ->addColumn('upload', function ($row) {
                    $update_url = url('/upload-document/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $update_url . '" title="upload"><i class="fa fa-upload " ></i><span class="air-top-right txt-color-red padding-3" >&nbsp;</span></a>';
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.pendingCertificateStatus')
                //->addColumn('action', 'adminpanel.complaint.pendingCertificateAction')
                ->addColumn('view', function ($row) {
                    $view_url = url('/view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'upload', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.pendingRecoveryList', ['pendingCount' => $pendingCount, 'rejectCount' => $rejectCount, 'approveCount' => $approveCount, 'pendingApprovalCount' => $pendingApprovalCount ]);
    }

    public function view($id)
    {
        $complainID = decrypt($id);
        $data = RegisterComplaint::with('provinces', 'districts', 'establishments', 'labouroffices')->find($complainID);
        //print_r($data); exit();
        $complaintdocuments = ComplaintDocument::where('ref_no', $complainID)->get();

        return view('adminpanel.complaint.view', ['data' => $data, 'complaintdocuments' => $complaintdocuments]);
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
        // $office = LabourOfficeDivision::where('status', 'Y')
        // ->where('is_delete', '0')
        // ->where('id','!=' ,$office_id)
        // ->get();
        $history = ComplaintHistory::where('status', 'Request_recovery')
        ->where('complaint_id', $complainID)
        ->orderBy('id', 'desc')
        ->get();

        $loOfficers = User::role(['Labour Officer','SLO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();

        $ftype = ForwardType::where('status', 'Y')
        ->where('is_delete', '0')
        ->orderBy('type_name', 'ASC')
        ->get();

        $remarks = ComplaintRemark::where('is_delete',0)->where('status','Y')->orderBy('remark_en', 'ASC')->get();

        $complaintstatus = complaintStatus::where('status', 'Y')
        ->where('is_delete', '0')
        ->where('complaint_status_type_id', 3)
        ->orderBy('status_en', 'ASC')
        ->get();

        $office = LabourOfficeDivision::where('status', 'Y')
        ->where('is_delete', '0')
        ->where('status', 'Y')
        ->orderBy('office_name_en', 'ASC')
        ->get();

        return view('adminpanel.complaint.action', ['data' => $data, 'history' => $history, 'labourOffice' => $office, 'loOfficers' => $loOfficers, 'forwardTypes' => $ftype, 'remarks' => $remarks, 'complaintstatus' => $complaintstatus]);
    }

    public function forwardActionRecovery(Request $request)
    {
        $request->validate([
            'labour_office_id' => 'required',
            'forward_type_id' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }

        $labour_office = LabourOfficeDivision::where('id', $request->labour_office_id)->first();

        if (!empty($labour_office)) {
            $sent_to_office_code = $labour_office->office_code;
        } else {
            $sent_to_office_code = NULL;
        }

        if ($request->forward_type_id == 3) {
            $status = 'Request_legal_certificate';
            $action_type = 'Pending_legal';
        // } else if ($request->forward_type_id == 4) {
        //     $status = 'Create_legal_certificate';
        } else if ($request->forward_type_id == 5) {
            $status = 'Request_plaint_charge_sheet';
            $action_type = 'Pending_plaint_charge_sheet';
        // } else if ($request->forward_type_id == 6) {
        //     $status = 'Create_plaint_and_charge_sheet';
        } else if ($request->forward_type_id == 7) {
            $status = 'Request_approve_temp_close';
            $action_type = 'Pending_approve';
        }else if ($request->forward_type_id == 8) {
            $status = 'Request_approve_close';
            $action_type = 'Pending_approve';
        } else {
            $status = 'Forward';
            $action_type = 'Pending';
        }

        $complaint->complaint_status = $status;
        $complaint->action_type = $action_type;
        $complaint->current_office_id = $request->labour_office_id;
        $complaint->save();
        $id = $complaint->id;

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' forward to office '.$sent_to_office_code.' with status '.$status.'.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = $status;

        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;

        $insert['sent_to_office'] = $request->labour_office_id;
        $insert['sent_to_office_code'] = $sent_to_office_code;
        $insert['action_type'] = $action_type;
        if($request->remark_option == 'Other'){
            $insert['remark'] = $request->remark;
        } else {
            $insert['remark'] = $request->remark_option;
        }

        if($complaint->pref_lang == "SI") {
            if ($request->forward_type_id == 3) {
                $insert['status_des'] = "නීතිමය සහතිකය $sent_to_office_code කාර්යාලයේදී සකස් කරමින් තිබේ";
            // } else if ($request->forward_type_id == 4) {
            //     $insert['status_des'] = 'Legal certificate created';
            } else if ($request->forward_type_id == 5) {
                $insert['status_des'] = "පැමිණිල්ල හා චෝදනා පත්‍රය  $sent_to_office_code කාර්යාලයේදී සකස්කරමින් තිබේ";
            // } else if ($request->forward_type_id == 6) {
            //     $insert['status_des'] = 'Plaint & chart sheet created';
            } else if ($request->forward_type_id == 7) {
                $insert['status_des'] = "පැමිණිල්ල තාවකාලිකව වැසීම සඳහා අනුමැතිය අපේක්ෂිතය";
            } else if ($request->forward_type_id == 8) {
                $insert['status_des'] = "පැමිණිල්ල වැසීම සඳහා අනුමැතිය අපේක්ෂිතය";
            } else {
                $insert['status_des'] = "ඔබේ පැමිණිල්ල $sent_to_office_code කාර්යාලයට යොමුකර තිබේ";
            }
        } else if ($complaint->pref_lang == "TA") {
            if ($request->forward_type_id == 3) {
                $insert['status_des'] = "$sent_to_office_code அலுவலகத்தில் சட்டச் சான்றிதழ் தயார்படுத்தப்படுகின்றது";
            // } else if ($request->forward_type_id == 4) {
            //     $insert['status_des'] = 'Legal certificate created';
            } else if ($request->forward_type_id == 5) {
                $insert['status_des'] = "$sent_to_office_code அலுவலகத்தில் முறைப்பாடு மற்றும் குற்றப்பத்திரிகை தயார்படுத்தப்படுகின்றது";
            // } else if ($request->forward_type_id == 6) {
            //     $insert['status_des'] = 'Plaint & chart sheet created';
            } else if ($request->forward_type_id == 7) {
                $insert['status_des'] = "முறைப்பாட்டை தற்காலிகமாக மூடுவதற்கான ஒப்புதலுக்காக காத்திருப்பு ";
            } else if ($request->forward_type_id == 8) {
                $insert['status_des'] = "முறைப்பாட்டை மூடுவதற்கான ஒப்புதலுக்காக காத்திருப்பு ";
            } else {
                $insert['status_des'] = "உங்களது முறைப்பாடு $sent_to_office_code அலுவலகத்திற்கு அனுப்பப்பட்டுள்ளது";
            }
        } else {
            if ($request->forward_type_id == 3) {
                $insert['status_des'] = "Processing legal certificate at $sent_to_office_code office";
            // } else if ($request->forward_type_id == 4) {
            //     $insert['status_des'] = 'Legal certificate created';
            } else if ($request->forward_type_id == 5) {
                $insert['status_des'] = "Processing plaint & chart sheet at $sent_to_office_code office";
            // } else if ($request->forward_type_id == 6) {
            //     $insert['status_des'] = 'Plaint & chart sheet created';
            } else if ($request->forward_type_id == 7) {
                $insert['status_des'] = "Approval waiting to temporarily close the complaint";
            } else if ($request->forward_type_id == 8) {
                $insert['status_des'] = "Approval waiting to close the complaint";
            } else {
                $insert['status_des'] = "Your Complaint has forward to $sent_to_office_code office";
            }
        }


        $insert['show_status'] = 'Ext';
        $insert['forward_type_id'] = $request->forward_type_id;
        $insert['user_id'] = Auth::user()->id;
        $insert['complaint_status_id'] = 0;
        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status '.$status.'.');


        $labourofficedetails = LabourOfficeDivision::where('id', $request->labour_office_id)->first();

        if($complaint->complainant_email != ''){

            $mailitem = MailTemplate::where('status', 'Y')
                    ->where('is_delete', 0)
                    ->where('id', 4)
                    ->get();

            if($complaint->pref_lang == 'EN'){
                $e_sub = $mailitem[0]->mail_template_name_en;
                // $e_body = $mailitem[0]->body_content_en;
                $e_name = $mailitem[0]->mail_template_name_en;

                $complainantName = $complaint->complainant_f_name;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]'];

                $variableData = [$labourofficedetails->office_name_en,$complaint->external_ref_no];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

                $email_body = 'Dear'.' '.$complaint->complainant_f_name.', '.$e_body;

            } else if($complaint->pref_lang == 'SI'){
                $e_sub = $mailitem[0]->mail_template_name_sin;
                // $e_body = $mailitem[0]->body_content_sin;
                $e_name = $mailitem[0]->mail_template_name_sin;

                $complainantName = $complaint->complainant_f_name_si;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]'];

                $variableData = [$labourofficedetails->office_name_sin,$complaint->external_ref_no];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

                $email_body = 'හිතවත්'.' '.$complaint->complainant_f_name_si.', '.$e_body;

            } else if($complaint->pref_lang == 'TA'){
                $e_sub = $mailitem[0]->mail_template_name_tam;
                // $e_body = $mailitem[0]->body_content_tam;
                $e_name = $mailitem[0]->mail_template_name_tam;

                $complainantName = $complaint->complainant_f_name_ta;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]'];

                $variableData = [$labourofficedetails->office_name_tam,$complaint->external_ref_no];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

                $email_body = 'அன்பார்ந்த'.' '.$complaint->complainant_f_name_ta.', '.$e_body;
            }

            // \Mail::send('mail.complaint-mail',
            //     array(
            //     'ref_no' => $complaint->external_ref_no,
            //     'date' => $complaint->created_at,
            //         'name' => $complainantName,
            //         'subject' => $e_sub,
            //         'body' => $email_body,
            //     ), function($message) use ($e_name, $complaint)
            // {
            //     $message->from('cms@labourdept.gov.lk');
            //     $message->to($complaint->complainant_email)->subject($e_name);
            // });
        }


        if($complaint->complainant_mobile != ''){

            $smsitem = SmsTemplate::where('status', 'Y')
                ->where('is_delete', 0)
                ->where('id', 4)
                ->get();

            // dd($smsitem);

            if($complaint->pref_lang == 'EN'){
                $s_sub = $smsitem[0]->sms_template_name_en;
                // $s_body = $smsitem[0]->body_content_en;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[COMPLAINANTNAME]'];

                $variableData = [$labourofficedetails->office_name_en,$complaint->external_ref_no,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

                $sms_body = $s_body;

            } else if($complaint->pref_lang == 'SI'){
                $s_sub = $smsitem[0]->sms_template_name_sin;
                // $s_body = $smsitem[0]->body_content_sin;

                $variables = ['[OFFICENAME]','REFERENCENUMBER','[COMPLAINANTNAME]'];

                $variableData = [$labourofficedetails->office_name_sin,$complaint->external_ref_no,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

                $sms_body = $s_body;

            } else if($complaint->pref_lang == 'TA'){
                $s_sub = $smsitem[0]->sms_template_name_tam;
                // $s_body = $smsitem[0]->body_content_tam;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[COMPLAINANTNAME]'];

                $variableData = [$labourofficedetails->office_name_sin,$complaint->external_ref_no,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                $sms_body = $s_body;
            }

            // $session= MobitelSms::createSession('','esmsusr_uqt','2L@boUr$m$','');
            // MobitelSms::sendMessagesMultiLang($session,'Labour Dept','Dear '.$complaint->complainant_f_name.','.$content1.' '.$sent_to_office_code.''.$content2,array($request->complainant_mobile),0);
            // MobitelSms::closeSession($session);

            $mobitelSms = new MobitelSms();
            $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
            $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($complaint->complainant_mobile),0);
            $mobitelSms->closeSession($session);

            \SmsLog::addToLog($complaint->complainant_f_name.' '.$complaint->complainant_l_name, $complaint->complainant_mobile, $sms_body);
        }


        return redirect()->route('pending-recovery-list')
            ->with('success', 'Complaint forward successfully.');
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

        if(!empty($labour_office)){
            $sent_to_office_code = $labour_office->office_code;
        }else{
            $sent_to_office_code = NULL;
        }

        $complaint->complaint_status = $request->action_type;
        $complaint->current_office_id = $request->labour_office_id;
        $complaint->action_type = 'Pending';
        $complaint->save();

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' status '.$request->action_type.'.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = $request->action_type;

        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;

        $insert['sent_to_office'] = $request->labour_office_id;
        $insert['sent_to_office_code'] = $sent_to_office_code;
        $insert['action_type'] = 'Pending';
        $insert['remark'] = $request->remark;
        $insert['forward_type_id'] = 0;
        $insert['complaint_status_id'] = 0;
        $insert['user_id'] = Auth::user()->id;

        if($request->action_type == 'Recovered'){

            if($complaint->pref_lang == "SI") {
                $insert['status_des'] = 'යථා තත්වයට පත්කරන ලදී';
            } else if($complaint->pref_lang == "TA") {
                $insert['status_des'] = 'மீட்டெடுக்கப்பட்டது';
            } else {
                $insert['status_des'] = 'Recovered';
            }
            $forward = 'Recovered';
        } else {

            if($complaint->pref_lang == "SI") {
                $insert['status_des'] = "ඉල්ලීම ප්‍රතික්ෂේප කරන ලදී. ඔබගේ පැමිණිල්ල නැවත $sent_to_office_code කාර්යාලය වෙත යවන ලදී";
            } else if($complaint->pref_lang == "TA") {
                $insert['status_des'] = "கோரிக்கை நிராகரிக்கப்பட்டது, உங்கள் முறைப்பாடு $sent_to_office_code அலுவலகத்திற்கு திருப்பி அனுப்பப்பட்டுள்ளது";
            } else {
                $insert['status_des'] = "Request reject, Your Complaint has forward back to $sent_to_office_code office";
            }
            $forward = 'Request rejected';
        }

        $insert['show_status'] = 'Ext';
        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status '.$request->action_type.'.');

        return redirect()->route('pending-recovery-list')
            ->with('success', "$forward successfully.");

    }

    public function pendinglist(Request $request)
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
                ->where('action_type', '=', 'Pending_recovery')
                // ->orWhere('complaint_status','=','Request - Issue Certification')
                // ->orWhere('complaint_status','=','Approved - Issue Certification')
                // ->orWhere('complaint_status','=','Request - Create Plaint & Chart Sheet')
                // ->orWhere('complaint_status','=','Created Plaint & Chart Sheet')
                ->where('current_office_id', $office_id)->orderBy('id', 'DESC');
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                        ->count();
                    $edit_url = "";
                    $status_url = url('/complaint-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="' . $status_url . '" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> ' . $statusCount;
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.actionsStatus')
                ->addColumn('action', 'adminpanel.complaint.actionsAction')
                // ->editColumn('created_at', function ($request) {
                //     return $request->created_at->format('Y-m-d'); // human readable format
                // })
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
                    $view_url = url('/view/' . encrypt($row->id) . '');
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
                ->rawColumns(['status', 'created_at', 'action', 'upload', 'modify', 'view', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.recoveryPendingList', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount, 'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

}

