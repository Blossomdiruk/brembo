<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use DataTables;
use Illuminate\Http\Request;
use App\Models\RegisterComplaint;
use App\Http\Controllers\Controller;
use App\Models\ComplaintDocument;
use Illuminate\Support\Facades\Auth;
use App\Models\ComplaintHistory;
use App\Models\LabourOfficeDivision;

class ClosedListController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:closed-list|view-closed-complaint', ['only' => ['list']]);
        $this->middleware('permission:view-closed-complaint', ['only' => ['view']]);
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
            $data = RegisterComplaint::select('*')->where('complaint_status', 'Closed')->where('current_office_id', $office_id);
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                })
                ->addColumn('status', 'adminpanel.complaint.actionsStatus')
                ->addColumn('view', function ($row) {
                    $view_url = url('/view-closed-complaint/' . encrypt($row->id) . '');
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
                ->rawColumns(['created_at', 'status', 'view', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.closed_list', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount,'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
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
