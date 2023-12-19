<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use DataTables;
use Illuminate\Http\Request;
use App\Models\ComplaintHistory;
use App\Models\RegisterComplaint;
use App\Http\Controllers\Controller;
use App\Models\LabourOfficeDivision;
use App\Models\User;
use App\Models\ComplaintDocument;
use App\Models\ComplaintStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TemporaryClosedListController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:temporary-closed-list|temporary-closed-action|view-temporary-closed-complaint', ['only' => ['list']]);
        $this->middleware('permission:temporary-closed-action', ['only' => ['complaintAction', 'statusUpdate', 'close', 'reopen']]);
        $this->middleware('permission:view-temporary-closed-complaint', ['only' => ['view']]);
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
            $data = RegisterComplaint::select('*')->where('complaint_status','Tempclosed')->where('current_office_id',$office_id);
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('status', function ($row) {
                //     $edit_url = "";
                //     $status_url = url('/complaint-status-history/' . encrypt($row->id) . '');
                //     $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a>';
                //     return $btn;
                // })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                  })
                ->addColumn('status', 'adminpanel.complaint.actionsStatus')
                ->addColumn('action', function ($row) {
                    $action_url = url('/temporary-closed-action/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $action_url . '" title="action"><i class="fa fa-mail-forward" ></i><span class="air-top-right txt-color-red padding-3" >&nbsp;</span></a>';
                    return $btn;
                })
                ->addColumn('view', function ($row) {
                    $view_url = url('/view-temporary-closed-complaint/' . encrypt($row->id) . '');
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
                ->rawColumns(['created_at', 'status', 'action', 'view', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.temporary_closed_list',['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount,'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

    public function complaintAction($id)
    {
        $complainID = decrypt($id);
        $office_id = Auth::user()->office_id;
        $data = RegisterComplaint::find($complainID);
        $office = LabourOfficeDivision::where('status', 'Y')
                ->where('is_delete', '0')
                ->where('status', 'Y')
                ->where('id','!=' ,$office_id)
                ->orderBy('office_name_en', 'ASC')
                ->get();
        // $role = Auth::user()->roles->pluck('name','id');

        $loOfficers = User::where('status', 'Y')->where('is_delete', 0)->get();

        $complaintstatus = complaintStatus::where('status', 'Y')
                                            ->where('is_delete', '0')
                                            ->where('complaint_status_type_id', 7)
                                            ->orderBy('status_en', 'ASC')
                                            ->get();

        return view('adminpanel.complaint.temporary_closed_action', ['data' => $data,'labourOffice' => $office, 'loOfficers'=> $loOfficers, 'complaintstatus' => $complaintstatus]);
    }

    public function reopen(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'remark' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if(!empty($labour_office)){
            $sent_from_office_code = $labour_office->office_code;
        }else{
            $sent_from_office_code = NULL;
        }

        $complaint->complaint_status = 'Ongoing';
        $complaint->action_type = 'Ongoing';
        $complaint->current_office_id = Auth::user()->office_id;
        $complaint->save();

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' is Reopened.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Reopen';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = NULL;
        $insert['sent_to_office_code'] = NULL;
        $insert['action_type'] = 'Reopen';
        $insert['remark'] = $request->remark;
        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['forward_type_id'] = 0;
        $insert['complaint_status_id'] = $request->complaint_status_id;
        if($complaint->pref_lang == "SI") {
            $insert['status_des'] = 'පැමිණිල්ල නැවත විවෘත කරන්න';
        } else if($complaint->pref_lang == "TA") {
            $insert['status_des'] = 'முறைப்பாட்டை மீள ஆரம்பிக்கவும் ';
        } else {
            $insert['status_des'] = 'Complaint Reopen';
        }
        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status Reopen.');

        DB::commit();
        return redirect()->route('temporary-closed-list')->with('success', 'Complaint reopen.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }

    }

    public function close(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'complaint_status_id' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if(!empty($labour_office)){
            $sent_from_office_code = $labour_office->office_code;
        }else{
            $sent_from_office_code = NULL;
        }

        $complaint->complaint_status = 'Closed';
        $complaint->action_type = 'Closed';
        $complaint->current_office_id = Auth::user()->office_id;
        $complaint->save();

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' is Closed.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Closed';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = NULL;
        $insert['sent_to_office_code'] = NULL;
        $insert['action_type'] = 'Closed';
        $insert['remark'] = $request->remark;
        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['forward_type_id'] = 0;
        $insert['complaint_status_id'] = $request->complaint_status_id;

        if($complaint->pref_lang == "SI") {
            $insert['status_des'] = 'පැමිණිල්ල වසා ඇත';
        } else if($complaint->pref_lang == "TA") {
            $insert['status_des'] = 'முறைப்பாடு மூடப்பட்டுள்ளது';
        } else {
            $insert['status_des'] = 'Complaint closed';
        }
        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status Closed.');

        DB::commit();
        return redirect()->route('temporary-closed-list')->with('success', 'Complaint closed.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }

    }

    public function statusUpdate(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'remark' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if(!empty($labour_office)){
            $sent_from_office_code = $labour_office->office_code;
        }else{
            $sent_from_office_code = NULL;
        }

        $complaint->complaint_status = 'Update';
        $complaint->current_office_id = Auth::user()->office_id;
        $complaint->save();

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Update';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = NULL;
        $insert['sent_to_office_code'] = NULL;
        $insert['action_type'] = 'Update';
        $insert['remark'] = $request->remark;
        $insert['show_status'] = 'Int';
        $insert['forward_type_id'] = 0;
        $insert['user_id'] = Auth::user()->id;
        $insert['complaint_status_id'] = $request->complaint_status_id;

        if($complaint->pref_lang == "SI") {
            $insert['status_des'] = 'පැමිණිල්ලේ තත්වය යාවත්කාලීන කර තිබේ';
        } else if($complaint->pref_lang == "TA") {
            $insert['status_des'] = 'முறைப்பாட்டு நிலை புதுப்பிக்கப்பட்டது';
        } else {
            $insert['status_des'] = 'Complaint status updated';
        }

        ComplaintHistory::insert($insert);

        DB::commit();
        return redirect()->route('temporary-closed-list')->with('success', 'Complaint status updated successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }

    }

    public function view($id)
    {
        $complainID = decrypt($id);
        $data = RegisterComplaint::with('provinces', 'districts', 'establishments', 'labouroffices')->find($complainID);

        $complaintdocuments = ComplaintDocument::where('ref_no', $complainID)->get();

        $complaintstatusdetails = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'desc')->get();

        return view('adminpanel.complaint.view', ['data' => $data, 'complaintdocuments' => $complaintdocuments, 'complaintstatusdetails' => $complaintstatusdetails]);
    }

}

