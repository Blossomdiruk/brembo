<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisterComplaint;
use App\Models\Complain_Category;
use DataTables;
use Monolog\Registry;
use Symfony\Component\Console\Input\Input;
use App\Models\ComplaintStatus;
use App\Models\ComplaintHistory;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MailTemplate;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use App\Models\LabourOfficeDivision;
use App\Models\ComplaintRemark;
use App\Models\ComplaintStatusType;

class SearchComplaintController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:search-complaint', ['only' => ['index']]);
    }

    public function index(Request $request)
    {

        $complaintcategories = Complain_Category::where('status', 'Y')
                                                ->orderBy('category_name_en', 'ASC')
                                                ->get();

        $employers = RegisterComplaint::select('employer_name')
            ->groupBy('employer_name')
            ->orderBy('employer_name', 'ASC')
            ->get();

        $status = RegisterComplaint::select('complaint_status')
            ->groupBy('complaint_status')
            ->orderBy('complaint_status', 'ASC')
            ->get();

        $complaintstatus = ComplaintStatus::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('status_en', 'ASC')
            ->get();

        $provinces = Province::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('province_name_en', 'ASC')
            ->get();

        $districts = District::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('district_name_en', 'ASC')
            ->get();

        $cities = City::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('city_name_en', 'ASC')
            ->get();

        $useroffice = LabourOfficeDivision::find(Auth::user()->office_id);

        if(Auth::user()->office_id == 83){
            $loOfficers = User::role(['Labour Officer','SLO'])
            ->where('is_delete', 0)
            ->where('status', 'Y')
            ->get();
        } else {
            if($useroffice->office_type_id == 3){
                $loOfficers = User::role(['Labour Officer','DCL','ACL'])
                    ->where('office_id', Auth::user()->office_id)
                    ->where('is_delete', 0)
                    ->where('status', 'Y')
                    ->get();
            } else if ($useroffice->office_type_id == 4 || $useroffice->office_type_id == 5){
                $loOfficers = User::role(['Labour Officer','SLO','ACL'])
                    ->where('office_id', Auth::user()->office_id)
                    ->where('is_delete', 0)
                    ->where('status', 'Y')
                    ->get();
            } else {
                if($useroffice->id == 2){
                    $loOfficers = User::role(['Labour Officer','SLO','IR LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else if($useroffice->id == 12){
                    $loOfficers = User::role(['Labour Officer','SLO','Termination Division LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else if($useroffice->id == 13){
                    $loOfficers = User::role(['Labour Officer','SLO','EPF LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else if($useroffice->id == 14){
                    $loOfficers = User::role(['Labour Officer','SLO','Enforcement Division LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else if($useroffice->id == 15){
                    $loOfficers = User::role(['Labour Officer','SLO','WCA LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else if($useroffice->id == 16){
                    $loOfficers = User::role(['Labour Officer','SLO','PRT LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else if($useroffice->id == 17){
                    $loOfficers = User::role(['Labour Officer','SLO','SID LO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                } else {
                    $loOfficers = User::role(['Labour Officer','SLO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();
                }
            }
        }

        $mailtemplate = MailTemplate::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('mail_template_title', 'ASC')
            ->get();

        $officedivisions = LabourOfficeDivision::where('status', 'Y')
            ->where('is_delete', '0')
            ->get();

        $remarks = ComplaintRemark::where('status', 'Y')
                ->where('is_delete', '0')
                ->where('remark_en', '!=' ,'Other')
                ->orderBy('remark_en', 'ASC')
                ->get();

        $statustypes = ComplaintStatusType::where('status', 'Y')
                ->where('is_delete', '0')
                ->orderBy('type_name_en', 'ASC')
                ->get();

        if (request()->ajax()) {
            $query = RegisterComplaint::leftJoin('users', 'users.id', '=', 'register_complaints.lo_officer_id')
            ->select('register_complaints.id', 'register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category','register_complaints.complaint_status' , 'register_complaints.created_at', 'register_complaints.employer_name', 'users.name as lo_name', 'register_complaints.current_office_id');

            // ->selectRaw("REPLACE(register_complaints.complaint_status,'_',' ')")

            if (!empty($request->external_ref_no)) {

                $query->where('external_ref_no', 'LIKE', '%' . $request->external_ref_no . '%');
            }
            if (!empty($request->ref_no)) {

                $query->where('ref_no', 'LIKE', '%' . $request->ref_no . '%');
            }
            if (!empty($request->complainant_identify_no)) {

                $query->where('complainant_identify_no', 'LIKE', '%' . $request->complainant_identify_no . '%');
            }
            if (!empty($request->complainant_mobile)) {

                $query->where('complainant_mobile', 'LIKE', '%' . $request->complainant_mobile . '%');
            }
            if (!empty($request->complainant_full_name)) {

                $query->where('complainant_full_name', 'LIKE', '%' . $request->complainant_full_name . '%');
            }
            if (!empty($request->complain_category)) {
                // $test = explode(',', 'complain_category');
                //     // dd($test);
                // $query->where('complain_category', 'LIKE', '%'.$test.'%');

                $query->where('complain_category', 'LIKE', '%' . $request->complain_category . '%');
            }
            if (!empty($request->complaint_status)) {

                $query->where('complaint_status', 'LIKE', '%' . $request->complaint_status . '%');
            }
            if ($request->from_date != '') {
                $query->where('register_complaints.created_at', '>', $request->from_date);
            }
            if ($request->to_date != '') {

                $query->where('register_complaints.created_at', '<', $request->to_date);
            }
            if (!empty($request->employer_name)) {

                $query->where('employer_name', 'LIKE', '%' . $request->employer_name . '%');
            }
            if (!empty($request->epf_no)) {

                $query->where('epf_no', 'LIKE', '%' . $request->epf_no . '%');
            }

            if ($request->province_id != '') {
                $query->where('province_id', '=', $request->province_id);
            }

            if ($request->district_id != '') {
                $query->where('district_id', '=', $request->district_id);
            }

            if ($request->city_id != '') {
                $query->where('city_id', '=', $request->city_id);
            }

            if($request->employee_mem_no != ''){
                $query->where('employee_mem_no', 'LIKE', '%' . $request->employee_mem_no . '%');
            }

            if(!empty($request->labour_officer_id))
            {
                $query->where('register_complaints.lo_officer_id', '=' , $request->labour_officer_id);

            }

            if ($request->labour_office != '') {
                $query->where('current_office_id', '=', $request->labour_office);
            }

            if(!empty($request->status_type))
            {
                if(!empty($request->updated_status) || !empty($request->remark) ){
                    $stypearr = ComplaintStatus::where('status', 'Y')
                            ->where('is_delete', '0')
                            ->where('complaint_status_type_id', $request->status_type)
                            ->orderBy('status_en', 'ASC')
                            ->pluck('id');


                    $query->leftJoin('complaint_histories', function($query)
                    {
                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                    ->whereRaw('complaint_histories.id IN (select MAX(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                    ->select('complaint_status_id');
                    })
                    ->whereIn('complaint_status_id', $stypearr);
                    if(!empty($request->updated_status)){
                        $query->where('complaint_status_id','=', $request->updated_status);
                    }
                    if(!empty($request->remark)){
                        $query->where('remark','=', $request->remark);
                    }
                    $query->select('register_complaints.id','register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name');
                } else {
                    if($request->status_type == 1){
                        $query->where('action_type', 'Pending');
                    }
                    if($request->status_type == 2){
                        $query->where('action_type', 'Ongoing');
                    }
                    if($request->status_type == 3){
                        $query->where('action_type', 'Pending_recovery');
                    }
                    if($request->status_type == 4){
                        $query->where('action_type', 'Pending_legal');
                    }
                    if($request->status_type == 5){
                        $query->where('action_type', 'Pending_plaint_charge_sheet');
                    }
                    if($request->status_type == 6){
                        $query->where('action_type', 'Tempclosed');
                    }
                    if($request->status_type == 7){
                        $query->where('action_type', 'Closed');
                    }
                    if($request->status_type == 8){
                        $query->where('action_type', 'Waiting');
                    }
                }
            } else if (!empty($request->remark)){
                $query->leftJoin('complaint_histories', function($query)
                {
                $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                ->whereRaw('complaint_histories.id IN (select MAX(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                ->select('remark');
                })
                ->where('remark','=', $request->remark)
                ->select('register_complaints.id','register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name');
            }

            if (!empty($request->mail_template)) {
                $query->where('mail_histories.template_id', '=', $request->mail_template)
                    ->leftJoin('mail_histories', 'mail_histories.complaint_id', '=', 'register_complaints.id')
                    ->select('register_complaints.id', 'register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name');
            }

            $data = $query->orderBy('register_complaints.created_at', 'DESC')->get();

            return datatables()->of($data)
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
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d'); // human readable format
                })
                ->addColumn('status', function ($row) {
                    $statusCount = ComplaintHistory::where('complaint_id', $row->id)
                        ->count();
                    $edit_url = "";
                    $status_url = url('/complaint-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="' . $status_url . '" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> ' . $statusCount;
                    return $btn;
                })
                ->rawColumns(['online_manual','status'])
                ->make(true);
        }
        return view('adminpanel.searchcomplaint.index', compact('complaintcategories', 'employers', 'status', 'complaintstatus', 'provinces', 'districts', 'cities', 'loOfficers', 'mailtemplate', 'officedivisions', 'remarks', 'statustypes'));
    }

    public function getComplaintStatus(Request $request)
    {
        $types = ComplaintStatus::where('complaint_status_type_id', $request->status_type)
                        ->where('status','Y')
                        ->where('is_delete','0')
                        ->orderBy('status_en', 'ASC')
                        ->get();
        return response()->json($types);
    }
}
