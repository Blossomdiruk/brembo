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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ComplaintStatus;
use App\Models\ComplaintRemark;
use App\Models\ForwardType;

class PendingCertificateListController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:pending-certificate-list|pending-certificate-status-history|pending-certificate-action|pending-certificate-view|legal-certificate-list', ['only' => ['list']]);
        //$this->middleware('permission:legal-certificate-list', ['only' => ['list']]);
        $this->middleware('permission:pending-certificate-status-history', ['only' => ['complaintStatus']]);
        $this->middleware('permission:pending-certificate-action', ['only' => ['complaintAction', 'forward']]);
        $this->middleware('permission:pending-certificate-view', ['only' => ['view']]);
        $this->middleware('permission:legal-certificate-pending-list', ['only' => ['pendinglist']]);
    }

    public function list(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $approveCount = RegisterComplaint::where('complaint_status','Approved - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Rejected - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('action_type','Pending_legal')
                            ->where('current_office_id',$office_id)
                            ->count();

        $pendingApprovalCount = RegisterComplaint::where('action_type','Pending_approve')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('*')->where('action_type','Pending_legal')->where('current_office_id',$office_id);
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
                    $status_url = url('/pending-certificate-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-certificate-action/' . encrypt($row->id) . '');
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
                    $view_url = url('/pending-certificate-view/' . encrypt($row->id) . '');
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
                ->rawColumns(['status', 'action', 'upload', 'view', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.pendingCertificateList', ['pendingCount' => $pendingCount, 'rejectCount' => $rejectCount, 'approveCount' => $approveCount, 'pendingApprovalCount' => $pendingApprovalCount ]);
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
            ->where('status', 'Y')
            ->orderBy('office_name_en', 'ASC')
            ->get();

        $complaintstatus = complaintStatus::where('status', 'Y')
                                            ->where('is_delete', '0')
                                            ->where('complaint_status_type_id', 4)
                                            ->orderBy('status_en', 'ASC')
                                            ->get();

        $remarks = ComplaintRemark::where('is_delete',0)->where('status','Y')->orderBy('remark_en', 'ASC')->get();

        $history = ComplaintHistory::where('status', 'Request_legal_certificate')
        ->where('complaint_id', $complainID)
        ->orderBy('id', 'desc')
        ->get();

        $ftype = ForwardType::where('status', 'Y')
            ->where('is_delete', '0')
            ->where('id', 10)
            ->orderBy('type_name', 'ASC')
            ->get();

        return view('adminpanel.complaint.action_certificate', ['data' => $data, 'history' => $history, 'complaintstatus' => $complaintstatus, 'remarks' => $remarks, 'labourOffice' => $office, 'forwardTypes' => $ftype]);
    }

    public function forward(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

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

        if($request->action_type == 'Create_legal_certificate'){
            if($complaint->pref_lang == "SI") {
                $insert['status_des'] = 'නීතිමය සහතිකය නිර්මාණය කරන ලදී';
            } else if($complaint->pref_lang == "TA") {
                $insert['status_des'] = 'சட்டச் சான்றிதழ் தயாரிக்கப்பட்டது';
            } else {
                $insert['status_des'] = 'Legal certificate created';
            }
            $forward = 'Legal certificate created';
        } else {
            $insert['status_des'] = "Request reject, Your Complaint has forward back to $sent_to_office_code office";
            $forward = 'Request rejected';
        }

        $insert['show_status'] = 'Ext';
        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status '.$request->action_type.'.');

        DB::commit();
        return redirect()->route('pending-certificate-list')
            ->with('success', "$forward successfully.");

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function approvedList(Request $request)
    {
        $office_id = Auth::user()->office_id;
        $approveCount = RegisterComplaint::where('complaint_status','Approved - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Rejected - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('complaint_status','Pending - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('*')->where('complaint_status','Approved - Issue Certification')->where('current_office_id',$office_id)->orderBy('id', 'DESC');
        //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                                        ->count();
                    $edit_url = "";
                    $status_url = url('/pending-certificate-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-certificate-action/' . encrypt($row->id) . '');
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
                    $view_url = url('/pending-certificate-view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'upload', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.approvedCertificateList', ['approveCount' => $approveCount, 'rejectCount' => $rejectCount, 'pendingCount' => $pendingCount ]);
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
        $approveCount = RegisterComplaint::where('complaint_status','Approved - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Rejected - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingCount = RegisterComplaint::where('complaint_status','Pending - Issue Certification')
                            ->where('current_office_id',$office_id)
                            ->count();

        if ($request->ajax()) {
            $data = RegisterComplaint::select('*')->where('complaint_status','Rejected - Issue Certification')->where('current_office_id',$office_id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                                        ->count();
                    $edit_url = "";
                    $status_url = url('/pending-certificate-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $edit_url = "";
                    $status_url = url('/pending-certificate-action/' . encrypt($row->id) . '');
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
                    $view_url = url('/pending-certificate-view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'upload', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.rejectedCertificateList', ['approveCount' => $approveCount, 'rejectCount' => $rejectCount, 'pendingCount' => $pendingCount ]);
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
                ->where('action_type', '=', 'Pending_legal')
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
                // ->addColumn('action', 'adminpanel.complaint.actionsAction')
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
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
                ->rawColumns(['status', 'created_at', 'upload', 'modify', 'view', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.certificatePendingList', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount, 'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

    public function statusUpdate(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'remark_option6' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }

        $complaint->complaint_status = 'Update';
        // $complaint->action_type = 'Ongoing';
        $complaint->current_office_id = Auth::user()->office_id;
        $complaint->save();

        $status = ComplaintStatus::where('id', $request->complaint_status_id)->first();

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' status Update.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Update';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = NULL;
        $insert['sent_to_office_code'] = NULL;
        $insert['action_type'] = 'Ongoing';
        if($request->remark_option6 == 'Other'){
            $insert['remark'] = $request->remark;
        } else {
            $insert['remark'] = $request->remark_option6;
        }
        $insert['show_status'] = 'Int';
        $insert['forward_type_id'] = 0;
        $insert['user_id'] = Auth::user()->id;
        $insert['complaint_status_id'] = $request->complaint_status_id;
        $insert['status_updated_date'] = $request->status_updated_date;

        if($complaint->pref_lang == "SI") {
            $insert['status_des'] = ''.$status->status_si.' - යාවත්කාලීන කරන ලද දිනය '.$request->status_updated_date.'.';
        } else if($complaint->pref_lang == "TA") {
            $insert['status_des'] = ''.$status->status_ta.' - திகதி புதுப்பிக்கப்பட்டது '.$request->status_updated_date.'.';
        } else {
            $insert['status_des'] = ''.$status->status_en.' - Updated date '.$request->status_updated_date.'.';
        }

        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status '.$status->status_en.'');

        DB::commit();
        return redirect()->route('pending-certificate-list')
            ->with('success', 'Complaint status updated successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

}
