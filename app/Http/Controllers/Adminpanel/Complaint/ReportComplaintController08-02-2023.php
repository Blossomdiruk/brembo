<?php

namespace App\Http\Controllers\Adminpanel\Complaint;

use App\Http\Controllers\Controller;
use App\Models\LabourOfficeDivision;
use Illuminate\Http\Request;
use App\Models\RegisterComplaint;
use App\Models\Complain_Category;
use DataTables;
use Monolog\Registry;
use Symfony\Component\Console\Input\Input;
use App\Models\ComplaintStatus;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MailTemplate;
use Session;
use App\Models\ComplaintHistory;
use App\Models\Users;
use Carbon\Carbon;
use App\Models\ComplaintRemark;
use App\Models\ComplaintStatusType;
use DB;

class ReportComplaintController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:report-complaint', ['only' => ['reportcomplaint']]);
        $this->middleware('permission:report-complaint-year', ['only' => ['reportcomplaintyear']]);
        $this->middleware('permission:report-search', ['only' => ['reportsearch']]);
        $this->middleware('permission:report-complaint-eachact', ['only' => ['reportEachAct']]);
        $this->middleware('permission:report-complaint-eachoffice', ['only' => ['reportEachOffice']]);
        $this->middleware('permission:report-complaint-officerwise', ['only' => ['reportOfficerWise']]);
        $this->middleware('permission:report-complaint-officewise', ['only' => ['reportOfficeWise']]);
        $this->middleware('permission:report-complaint-by-period', ['only' => ['reportcomplaintbyperoid']]);
        $this->middleware('permission:report-time-analysis', ['only' => ['reporttimeanalysis']]);
    }

    public function reportcomplaint(Request $request)
    {
        $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->orderBy('office_name_en', 'ASC')
                    ->get();

        if(request()->ajax())
        {
            // if($request->from_date != '' && $request->labour_office != ''){
            //     $totalcount = RegisterComplaint::whereBetween('created_at', [$request->from_date, $request->to_date])->where('register_complaints.current_office_id','=', $request->labour_office)->count();
            //     $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->whereBetween('created_at', [$request->from_date, $request->to_date])->where('register_complaints.current_office_id','=', $request->labour_office)->count();
            //     $pendingcount = $totalcount - $closedcount;
            // } else if($request->from_date == '' && $request->labour_office != ''){
            //     $totalcount = RegisterComplaint::where('register_complaints.current_office_id','=', $request->labour_office)->count();
            //     $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
            //     $pendingcount = $totalcount - $closedcount;
            // } else {
            //     $totalcount = RegisterComplaint::count();
            //     $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->count();
            //     $pendingcount = $totalcount - $closedcount;
            // }

            // $data[] = array(
            //     "totalcount" => $totalcount,
            //     "closedcount" => $closedcount,
            //     "pendingcount" => $pendingcount,
            //   );

            // return datatables()->of($data)->make(true);

            $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
            $to_date = Carbon::parse($request->input('to_date'))->endOfDay();

            $today = Carbon::today();
            $todaydate = $today->toDateString();

            if($request->from_date != '' && $request->to_date != '' && $request->labour_office != ''){
                $totalcount = RegisterComplaint::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $pendingcount = $totalcount - $closedcount;

            } else if($request->from_date == '' && $request->to_date == '' && $request->labour_office != ''){
                $totalcount = RegisterComplaint::where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $pendingcount = $totalcount - $closedcount;

            } else if($request->from_date != '' && $request->to_date == '' && $request->labour_office != ''){
                $totalcount = RegisterComplaint::whereDate('created_at', '>=', $from_date)->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $closedcount = RegisterComplaint::whereDate('created_at', '>=', $from_date)->where('complaint_status', 'Closed')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $pendingcount = $totalcount - $closedcount;

            } else if($request->from_date == '' && $request->to_date != '' && $request->labour_office != ''){
                $totalcount = RegisterComplaint::whereDate('created_at', '<=', $to_date)->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $closedcount = RegisterComplaint::whereDate('created_at', '<=', $to_date)->where('complaint_status', 'Closed')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                $pendingcount = $totalcount - $closedcount;

            } else if($request->from_date != '' && $request->to_date != '' && $request->labour_office == ''){
                $totalcount = RegisterComplaint::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where('register_complaints.current_office_id','!=', NULL)->count();
                $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where('register_complaints.current_office_id','!=', NULL)->count();
                $pendingcount = $totalcount - $closedcount;

            } else if($request->from_date != '' && $request->to_date == '' && $request->labour_office == ''){
                $totalcount = RegisterComplaint::whereDate('created_at', '>=', $from_date)->where('register_complaints.current_office_id','!=', NULL)->count();
                $closedcount = RegisterComplaint::whereDate('created_at', '>=', $from_date)->where('complaint_status', 'Closed')->where('register_complaints.current_office_id','!=', NULL)->count();
                $pendingcount = $totalcount - $closedcount;

            } else if($request->from_date == '' && $request->to_date != '' && $request->labour_office == ''){
                $totalcount = RegisterComplaint::whereDate('created_at', '<=', $to_date)->where('register_complaints.current_office_id','!=', NULL)->count();
                $closedcount = RegisterComplaint::whereDate('created_at', '<=', $to_date)->where('complaint_status', 'Closed')->where('register_complaints.current_office_id','!=', NULL)->count();
                $pendingcount = $totalcount - $closedcount;

            } else {
                $totalcount = RegisterComplaint::where('register_complaints.current_office_id','!=', NULL)->count();
                $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->where('register_complaints.current_office_id','!=', NULL)->count();
                $pendingcount = $totalcount - $closedcount;
            }

            $data[] = array(
                "totalcount" => $totalcount,
                "closedcount" => $closedcount,
                "pendingcount" => $pendingcount,
              );


            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.period', compact('officedivisions'));
    }

    public function reportcomplaintyear(Request $request)
    {

        $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->get();

        if(request()->ajax())
        {
            if($request->from_year != '' && $request->labour_office != '' && $request->to_year != ''){
                $data = array();

                $fyear = $request->from_year;
                $tyear = $request->to_year;
                $count = $tyear - $fyear;

                for($i=0;$i < $count+1;$i++,$fyear++){

                        $totalcount[$i] = RegisterComplaint::where('created_at', 'LIKE', '%' .$fyear. '%')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                        $closedcount[$i] = RegisterComplaint::where('created_at', 'LIKE', '%' .$fyear. '%')->where('complaint_status', 'Closed')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                        $pendingcount[$i] = $totalcount[$i] - $closedcount[$i];


                    $data[] = array(
                        "year" => $fyear,
                        "totalcount" => $totalcount[$i],
                        "closedcount" => $closedcount[$i],
                        "pendingcount" => $pendingcount[$i],
                    );
                }
            } else if($request->from_year != '' && $request->labour_office != '' &&  $request->to_year == ''){

                $fromyear = $request->from_year;
                $fyear = date('Y', strtotime(RegisterComplaint::min('created_at')));
                $tyear = date('Y', strtotime(RegisterComplaint::max('created_at')));
                $count = (int)$tyear - (int)$fyear;

                for($i=0;$i < $count+1;$i++,$fyear++){

                        $totalcount[$i] = RegisterComplaint::where('created_at', 'LIKE', '%' .$fromyear. '%')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                        $closedcount[$i] = RegisterComplaint::where('created_at', 'LIKE', '%' .$fromyear. '%')->whereYear('created_at', $tyear)->where('complaint_status', 'Closed')->where('register_complaints.current_office_id','=', $request->labour_office)->count();
                        $pendingcount[$i] = $totalcount[$i] - $closedcount[$i];


                    $data[] = array(
                        "year" => $fromyear,
                        "totalcount" => $totalcount[$i],
                        "closedcount" => $closedcount[$i],
                        "pendingcount" => $pendingcount[$i],
                    );
                }

                // $totalcount = RegisterComplaint::count();
                // $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->count();
                // $pendingcount = $totalcount - $closedcount;

                // $data[] = array(
                //     "year" => '',
                //     "totalcount" => $totalcount,
                //     "closedcount" => $closedcount,
                //     "pendingcount" => $pendingcount,
                // );
            } else {
                $data[] = array(
                    "year" => '',
                    "totalcount" => '',
                    "closedcount" => '',
                    "pendingcount" => '',
                );
            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.year', compact('officedivisions'));
    }

    public function reportsearch(Request $request)
    {

        $complaintcategories = Complain_Category::where('status', 'Y')
                                                ->get();

        $employers = RegisterComplaint::select('employer_name')
                ->groupBy('employer_name')
                ->get();

        $status = RegisterComplaint::select('complaint_status')
                ->groupBy('complaint_status')
                ->get();

        $complaintstatus = ComplaintStatus::where('status', 'Y')
                ->where('is_delete', '0')
                ->get();

        $provinces = Province::where('status', 'Y')
                ->where('is_delete', '0')
                ->get();

        $districts = District::where('status', 'Y')
                ->where('is_delete', '0')
                ->get();

        $cities = City::where('status', 'Y')
                ->where('is_delete', '0')
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

        if(request()->ajax())
        {
            $query = RegisterComplaint::join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
            ->leftJoin('users', 'users.id', '=', 'register_complaints.lo_officer_id')
            ->select('register_complaints.id', 'register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name', 'users.name as lo_name', 'labour_offices_divisions.office_name_en', 'register_complaints.current_office_id');

            if(!empty($request->external_ref_no))
            {

                $query->where('external_ref_no', 'LIKE', '%'.$request->external_ref_no.'%');

            }
            if(!empty($request->ref_no))
            {

                $query->where('ref_no', 'LIKE', '%'.$request->ref_no.'%');

            }
            if(!empty($request->complainant_identify_no))
            {

                $query->where('complainant_identify_no', 'LIKE', '%'.$request->complainant_identify_no.'%');

            }
            if(!empty($request->complainant_mobile))
            {

                $query->where('complainant_mobile', 'LIKE', '%'.$request->complainant_mobile.'%');

            }
            if(!empty($request->complainant_full_name))
            {

                $query->where('complainant_full_name', 'LIKE', '%'.$request->complainant_full_name.'%');

            }
            if(!empty($request->complain_category))
            {
                // $test = explode(',', 'complain_category');
            //     // dd($test);
                // $query->where('complain_category', 'LIKE', '%'.$test.'%');

                $query->where('complain_category', 'LIKE', '%'.$request->complain_category.'%');

            }
            if(!empty($request->complaint_status))
            {

                $query->where('complaint_status', 'LIKE', '%'.$request->complaint_status.'%');

            }
            if($request->from_date != '')
            {
                $query->where('register_complaints.created_at', '>', $request->from_date);
            }
            if($request->to_date != '')
            {

                $query->where('register_complaints.created_at', '<', $request->to_date);
            }
            if(!empty($request->employer_name))
            {

                $query->where('employer_name', 'LIKE', '%'.$request->employer_name.'%');

            }

            if(!empty($request->epf_no))
            {

                $query->where('epf_no', 'LIKE', '%'.$request->epf_no.'%');

            }

            if($request->province_id != '')
            {
                $query->where('register_complaints.province_id', '=', $request->province_id);
            }

            if($request->district_id != '')
            {
                $query->where('register_complaints.district_id', '=', $request->district_id);
            }

            if($request->city_id != '')
            {
                $query->where('register_complaints.city_id', '=', $request->city_id);
            }

            if($request->employee_mem_no != ''){
                $query->where('employee_mem_no', 'LIKE', '%' . $request->employee_mem_no . '%');
            }

            if(!empty($request->labour_officer_id))
            {
                $query->where('lo_officer_id', '=' , $request->labour_officer_id);

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

            if(!empty($request->mail_template)){
                $query->where('mail_histories.template_id','=', $request->mail_template)
                ->leftJoin('mail_histories', 'mail_histories.complaint_id', '=', 'register_complaints.id')
                ->select('register_complaints.id','register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name');
            }

            $data = $query->get();

            return datatables()->of($data)
                    ->editColumn('created_at', function ($request) {
                        return $request->created_at->format('Y-m-d'); // human readable format
                    })
                    ->make(true);

        }
        return view('adminpanel.reportcomplaint.search', compact('complaintcategories', 'employers', 'status', 'complaintstatus', 'provinces', 'districts', 'cities', 'loOfficers', 'mailtemplate', 'officedivisions', 'remarks', 'statustypes'));
    }

    public function reportEachAct(Request $request)
    {

        $labouroffice = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->orderBy('office_name_en', 'ASC')
                    ->get();

        if(request()->ajax())
        {
            $labouroffice = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->orderBy('office_name_en', 'ASC')
                    ->get();

            $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
            $to_date = Carbon::parse($request->input('to_date'))->endOfDay();


            if(!empty($labouroffice)){


                foreach($labouroffice as $key => $item){

                    if($request->from_date != '' && $request->to_date != ''){

                    $approvecomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Pending_approve')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $pendingcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Pending')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $ongoingcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Ongoing')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $tempclosedcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'TempClosed')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $closedcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Closed')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $recoverycomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Pending_recovery')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $appealcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Waiting')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $legalcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Pending_legal')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $chargecomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('action_type', 'Pending_plaint_charge_sheet')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $totalcompaint = $closedcomplaintcount[$key] + $pendingcomplaintcount[$key] + $ongoingcomplaintcount[$key] + $tempclosedcomplaintcount[$key] + $legalcomplaintcount[$key] + $chargecomplaintcount[$key] + $recoverycomplaintcount[$key] + $appealcomplaintcount[$key] + $approvecomplaintcount[$key];


                    //performance report

                    $totalpendingcount = $pendingcomplaintcount[$key] + $approvecomplaintcount[$key] + $legalcomplaintcount[$key];

                    if($totalcompaint>0 && $totalpendingcount > 0) {

                        $performanceperentage = (($totalpendingcount/$totalcompaint) * 100);

                        $finalCalculation = round(100 - $performanceperentage);

                    } else {

                        $finalCalculation = 0;

                    }

                    if(Session::get('applocale') == 'ta'){
                        $office_name = $item->office_name_tam;
                    } else if (Session::get('applocale') == 'si'){
                        $office_name = $item->office_name_sin;
                    } else {
                        $office_name = $item->office_name_en;
                    }

                    $data[] = array(
                        "office" => $office_name,
                        'close' => $closedcomplaintcount[$key],
                        'action_pending' => $pendingcomplaintcount[$key],
                        'ongoing' => $ongoingcomplaintcount[$key],
                        'temporary_closed' => $tempclosedcomplaintcount[$key],
                        'legal'=> $legalcomplaintcount[$key],
                        'plaint'=> $chargecomplaintcount[$key],
                        'recovery'=> $recoverycomplaintcount[$key],
                        'appeal' => $appealcomplaintcount[$key],
                        'approve' => $approvecomplaintcount[$key],
                        "totalcount" => $totalcompaint,
                        'percentage' => $finalCalculation,
                        "rank" => ""
                    );

                } else if($request->from_date != '' && $request->to_date == ''){
                    $approvecomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Pending_approve')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $pendingcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Pending')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $ongoingcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Ongoing')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $tempclosedcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'TempClosed')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $closedcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Closed')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $recoverycomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Pending_recovery')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $appealcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Waiting')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $legalcomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Pending_legal')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $chargecomplaintcount[$key] = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('action_type', 'Pending_plaint_charge_sheet')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $totalcompaint = $closedcomplaintcount[$key] + $pendingcomplaintcount[$key] + $ongoingcomplaintcount[$key] + $tempclosedcomplaintcount[$key] + $legalcomplaintcount[$key] + $chargecomplaintcount[$key] + $recoverycomplaintcount[$key] + $appealcomplaintcount[$key] + $approvecomplaintcount[$key];

                    //performance report

                    $totalpendingcount = $pendingcomplaintcount[$key] + $approvecomplaintcount[$key] + $legalcomplaintcount[$key];

                    if($totalcompaint>0 && $totalpendingcount > 0) {

                        $performanceperentage = (($totalpendingcount/$totalcompaint) * 100);

                        $finalCalculation = round(100 - $performanceperentage);

                    } else {

                        $finalCalculation = 0;

                    }

                    if(Session::get('applocale') == 'ta'){
                        $office_name = $item->office_name_tam;
                    } else if (Session::get('applocale') == 'si'){
                        $office_name = $item->office_name_sin;
                    } else {
                        $office_name = $item->office_name_en;
                    }

                    $data[] = array(
                        "office" => $office_name,
                        'close' => $closedcomplaintcount[$key],
                        'action_pending' => $pendingcomplaintcount[$key],
                        'ongoing' => $ongoingcomplaintcount[$key],
                        'temporary_closed' => $tempclosedcomplaintcount[$key],
                        'legal'=> $legalcomplaintcount[$key],
                        'plaint'=> $chargecomplaintcount[$key],
                        'recovery'=> $recoverycomplaintcount[$key],
                        'appeal' => $appealcomplaintcount[$key],
                        'approve' => $approvecomplaintcount[$key],
                        "totalcount" => $totalcompaint,
                        'percentage' => $finalCalculation,
                        "rank" => ""
                    );

                }else{
                    $approvecomplaintcount[$key] = RegisterComplaint::where('action_type', 'Pending_approve')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $pendingcomplaintcount[$key] = RegisterComplaint::where('action_type', 'Pending')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $ongoingcomplaintcount[$key] = RegisterComplaint::where('action_type', 'Ongoing')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $tempclosedcomplaintcount[$key] = RegisterComplaint::where('action_type', 'TempClosed')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $closedcomplaintcount[$key] = RegisterComplaint::where('action_type', 'Closed')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $recoverycomplaintcount[$key] = RegisterComplaint::where('action_type', 'Pending_recovery')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $appealcomplaintcount[$key] = RegisterComplaint::where('action_type', 'Waiting')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $legalcomplaintcount[$key] = RegisterComplaint::where('action_type', 'Pending_legal')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $chargecomplaintcount[$key] = RegisterComplaint::where('action_type', 'Pending_plaint_charge_sheet')
                        ->where('current_office_id', $item->id)
                        ->count();

                    $totalcompaint = $closedcomplaintcount[$key] + $pendingcomplaintcount[$key] + $ongoingcomplaintcount[$key] + $tempclosedcomplaintcount[$key] + $legalcomplaintcount[$key] + $chargecomplaintcount[$key] + $recoverycomplaintcount[$key] + $appealcomplaintcount[$key] + $approvecomplaintcount[$key];

                    //performance report
                    
                    $totalpendingcount = $pendingcomplaintcount[$key] + $approvecomplaintcount[$key] + $legalcomplaintcount[$key];

                    if($totalcompaint>0 && $totalpendingcount > 0) {

                        $performanceperentage = (($totalpendingcount/$totalcompaint) * 100);

                        $finalCalculation = round(100 - $performanceperentage);

                    } else {

                        $finalCalculation = 0;

                    }

                    if(Session::get('applocale') == 'ta'){
                        $office_name = $item->office_name_tam;
                    } else if (Session::get('applocale') == 'si'){
                        $office_name = $item->office_name_sin;
                    } else {
                        $office_name = $item->office_name_en;
                    }


                    $data[] = array(
                        "office" => $office_name,
                        'close' => $closedcomplaintcount[$key],
                        'action_pending' => $pendingcomplaintcount[$key],
                        'ongoing' => $ongoingcomplaintcount[$key],
                        'temporary_closed' => $tempclosedcomplaintcount[$key],
                        'legal'=> $legalcomplaintcount[$key],
                        'plaint'=> $chargecomplaintcount[$key],
                        'recovery'=> $recoverycomplaintcount[$key],
                        'appeal' => $appealcomplaintcount[$key],
                        'approve' => $approvecomplaintcount[$key],
                        "totalcount" => $totalcompaint,
                        'percentage' => $finalCalculation,
                        "rank" => ""
                    );
                }

                }
            }

            array_multisort(array_column( $data, 'percentage' ), SORT_DESC, $data);
            // rsort($data);

            // $array = collect($data)->sortBy('percentage')->reverse()->toArray();

            // $array = collect($data)->sortBy('percentage')->toArray();

            $arrlength = count($data);
            $rank = 1;
            $prev_rank = $rank;

            // for($x = 0; $x < $arrlength; $x++) {
            $count = 0;
            foreach($data as $key => $arr) {

                if ($count==0) {
                    //  echo $array[$x]."- Rank".($rank);

                     $data[$key]['rank'] = $rank;


                }
               elseif ($data[$count]['percentage'] != $data[$count-1]['percentage']) {

                    $rank++;
                    $prev_rank = $rank;
                    // echo $array[$x]."- Rank".($rank);

                    $data[$key]['rank'] = $rank;
               }

               else {
                    $rank++;

                    $data[$key]['rank'] = $prev_rank;
                    // echo $array[$x]."- Rank".($prev_rank);
                }

                $count++;

            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.eachact', compact('labouroffice'));
    }

    public function reportEachOffice(Request $request)
    {
        $labouroffice = LabourOfficeDivision::where('is_delete', '0')
                ->where('status', 'Y')
                ->orderBy('office_name_en', 'ASC')
                ->get();

        $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
        $to_date = Carbon::parse($request->input('to_date'))->endOfDay();

        if(request()->ajax())
        {
            $office = LabourOfficeDivision::where('is_delete', '0')
                ->where('status', 'Y')
                ->orderBy('office_name_en', 'ASC')
                ->get();

            if(!empty($office)){
                foreach($office as $item){
                    if($request->from_date != '' && $request->to_date != ''){
                        $manualcount = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                                    ->where('register_complaints.current_office_id','=', $item->id)
                                    ->leftJoin('complaint_histories', function($query)
                                    {
                                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                                    ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                                    ->select('user_id');
                                    })
                                    ->where('user_id','!=', 0)
                                    ->count();

                        $onlinecount = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                                    ->where('register_complaints.current_office_id','=', $item->id)
                                    ->leftJoin('complaint_histories', function($query)
                                    {
                                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                                    ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                                    ->select('user_id');
                                    })
                                    ->where('user_id','=', 0)
                                    ->count();

                    } else if($request->from_date != '' && $request->to_date == ''){
                        $manualcount = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                                    ->where('register_complaints.current_office_id','=', $item->id)
                                    ->leftJoin('complaint_histories', function($query)
                                    {
                                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                                    ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                                    ->select('user_id');
                                    })
                                    ->where('user_id','!=', 0)
                                    ->count();

                        $onlinecount = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                                    ->where('register_complaints.current_office_id','=', $item->id)
                                    ->leftJoin('complaint_histories', function($query)
                                    {
                                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                                    ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                                    ->select('user_id');
                                    })
                                    ->where('user_id','=', 0)
                                    ->count();

                    } else if($request->from_date == '' && $request->to_date != ''){
                        $manualcount = RegisterComplaint::whereDate('register_complaints.created_at', '<=', $to_date)
                                    ->where('register_complaints.current_office_id','=', $item->id)
                                    ->leftJoin('complaint_histories', function($query)
                                    {
                                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                                    ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                                    ->select('user_id');
                                    })
                                    ->where('user_id','!=', 0)
                                    ->count();

                        $onlinecount = RegisterComplaint::whereDate('register_complaints.created_at', '<=', $to_date)
                                    ->where('register_complaints.current_office_id','=', $item->id)
                                    ->leftJoin('complaint_histories', function($query)
                                    {
                                    $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                                    ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                                    ->select('user_id');
                                    })
                                    ->where('user_id','=', 0)
                                    ->count();

                    } else {
                        $manualcount = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->leftJoin('complaint_histories', function($query)
                            {
                            $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                            ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                            ->select('user_id');
                            })
                            ->where('user_id','!=', 0)
                            ->count();

                        $onlinecount = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->leftJoin('complaint_histories', function($query)
                            {
                            $query->on('register_complaints.id','=','complaint_histories.complaint_id')
                            ->whereRaw('complaint_histories.id IN (select MIN(a2.id) from complaint_histories as a2 join register_complaints as u2 on u2.id = a2.complaint_id group by u2.id)')
                            ->select('user_id');
                            })
                            ->where('user_id','=', 0)
                            ->count();
                    }

                    if(Session::get('applocale') == 'ta'){
                        $office_name = $item->office_name_tam;
                    } else if (Session::get('applocale') == 'si'){
                        $office_name = $item->office_name_sin;
                    } else {
                        $office_name = $item->office_name_en;
                    }

                    $data[] = array(
                        "office" => $office_name,
                        "manualcount" => $manualcount,
                        "onlinecount" => $onlinecount,
                    );
                }
            }


            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.eachoffice', compact('labouroffice'));
    }

    public function reportOfficerWise(Request $request)
    {
        // $loOfficers = User::role(13)
        //             ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
        //             ->select('users.id','users.name','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
        //             ->get();

        $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->get();

        if(request()->ajax())
        {
            $useroffice = LabourOfficeDivision::find($request->labour_office);

            if($request->labour_office != '') {
                // $loOfficers = User::role(13)
                // ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                // ->where('users.office_id','=', $request->labour_office)
                // ->where('users.status', 'Y')
                // ->where('users.is_delete',0)
                // ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                // ->get();

                if($useroffice->office_type_id == 3){
                    $loOfficers = User::role(['Labour Officer','DCL','ACL'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                } else if ($useroffice->office_type_id == 4 || $useroffice->office_type_id == 5){
                    $loOfficers = User::role(['Labour Officer','SLO','ACL'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                } else {
                    if($useroffice->id == 2){
                        $loOfficers = User::role(['Labour Officer','SLO','IR LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else if($useroffice->id == 12){
                        $loOfficers = User::role(['Labour Officer','SLO','Termination Division LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else if($useroffice->id == 13){
                        $loOfficers = User::role(['Labour Officer','SLO','EPF LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else if($useroffice->id == 14){
                        $loOfficers = User::role(['Labour Officer','SLO','Enforcement Division LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else if($useroffice->id == 15){
                        $loOfficers = User::role(['Labour Officer','SLO','WCA LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else if($useroffice->id == 16){
                        $loOfficers = User::role(['Labour Officer','SLO','PRT LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else if($useroffice->id == 17){
                        $loOfficers = User::role(['Labour Officer','SLO','SID LO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } else {
                        $loOfficers = User::role(['Labour Officer','SLO'])
                        ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                        ->where('users.office_id','=', $request->labour_office)
                        ->where('users.status', 'Y')
                        ->where('users.is_delete',0)
                        ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                        ->get();
                    } 
                }

            } else {
                $loOfficers = User::role(13)
                    ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                    ->where('users.status','Y')
                    ->where('users.is_delete', 0)
                    ->select('users.id','users.name','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                    ->get();

            }

            if($request->year != '' && $request->month != ''){
                if(count($loOfficers)){
                    foreach($loOfficers as $item){
                        $dateE = Carbon::createFromFormat('m Y', $request->month.' '.$request->year)->firstOfMonth();
                        $nextmonth = $request->month + 1;
                        $dateEE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth();

                        $previousbalance1 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                        ->where('register_complaints.created_at', '<' ,$dateE)
                        ->where('register_complaints.complaint_status', '!=', 'Closed')
                        ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                        ->where('register_complaints.complaint_status', '!=', 'Request_assign_lo')
                        ->where('register_complaints.complaint_status', '!=', 'Request_approve_close')
                        ->where('register_complaints.complaint_status', '!=', 'Request_approve_temp_close')
                            ->count();

                        //get camplaints that closed after searched month
                        $previousbalance2 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $previousbalance3 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $previousbalance = $previousbalance1 + $previousbalance2 + $previousbalance3;

                        $settledprevious1 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $settledprevious2 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $settledprevious3 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Request_approve_close')
                            ->count();

                        $settledprevious4 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Request_approve_temp_close')
                            ->count();

                        $settledprevious = $settledprevious1 + $settledprevious2 + $settledprevious3 + $settledprevious4;    

                        $previoustotalcount = $previousbalance + $settledprevious;

                        $receivedcount = RegisterComplaint::whereYear('register_complaints.created_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.created_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->count();

                        $settledcount1 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $settledcount2 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $settledcount3 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Request_approve_close')
                            ->count();

                        $settledcount4 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Request_approve_temp_close')
                            ->count();

                        $settledcount = $settledcount1 + $settledcount2 + $settledcount3 + $settledcount4;

                        $balancecount = $previoustotalcount + $receivedcount - $settledcount;


                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $dateEE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth();

                        $lessthanone1 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateEE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                            ->where('register_complaints.complaint_status', '!=', 'Request_assign_lo')
                            ->where('register_complaints.complaint_status', '!=', 'Request_approve_close')
                            ->where('register_complaints.complaint_status', '!=', 'Request_approve_temp_close')
                            ->count();

                        //get camplaints that closed after searched month
                        $lessthanone2 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateEE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $lessthanone3 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateEE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $lessthanone = $lessthanone1 + $lessthanone2 + $lessthanone3;

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $dateE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $lessthanthree1 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                            ->where('register_complaints.complaint_status', '!=', 'Request_assign_lo')
                            ->where('register_complaints.complaint_status', '!=', 'Request_approve_close')
                            ->where('register_complaints.complaint_status', '!=', 'Request_approve_temp_close')
                            ->count();

                        //get camplaints that closed after searched month
                        $lessthanthree2 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $lessthanthree3 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $lessthanthree = $lessthanthree1 + $lessthanthree2 + $lessthanthree3;

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $morethanthree1 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                                ->where('created_at', '<' ,$dateS)
                                ->where('register_complaints.complaint_status', '!=', 'Closed')
                                ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                                ->where('register_complaints.complaint_status', '!=', 'Request_assign_lo')
                                ->where('register_complaints.complaint_status', '!=', 'Request_approve_close')
                                ->where('register_complaints.complaint_status', '!=', 'Request_approve_temp_close')
                                ->count();

                        //get camplaints that closed after searched month
                        $morethanthree2 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('created_at', '<' ,$dateS)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $morethanthree3 = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('created_at', '<' ,$dateS)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $morethanthree = $morethanthree1 + $morethanthree2 + $morethanthree3;

                        $total = $lessthanone + $lessthanthree + $morethanthree;

                        if(Session::get('applocale') == 'ta'){
                            $office_name = $item->office_name_tam;
                        } else if (Session::get('applocale') == 'si'){
                            $office_name = $item->office_name_sin;
                        } else {
                            $office_name = $item->office_name_en;
                        }

                        $data[] = array(
                            "lo" => $item->name,
                            "previousbalance" => $previousbalance,
                            "settledprevious" => $settledprevious,
                            "settledcount" => $settledcount,
                            "previoustotalcount" => $previoustotalcount,
                            "receivedcount" => $receivedcount,
                            "balancecount" => $balancecount,
                            "lessthanthree" => $lessthanthree,
                            "lessthanone" => $lessthanone,
                            "morethanthree" => $morethanthree,
                            "total" => $total
                        );

                    }

                } else {
                    $data[] = array(
                        "lo" => '',
                        "previousbalance" => '',
                        "settledprevious" => '',
                        "settledcount" => '',
                        "previoustotalcount" => '',
                        "receivedcount" => '',
                        "balancecount" => '',
                        "lessthanthree" => '',
                        "lessthanone" => '',
                        "morethanthree" => '',
                        "total" => ''
                    );
                }
            } else {
                $data[] = array(
                    "lo" => '',
                    "previousbalance" => '',
                    "settledprevious" => '',
                    "settledcount" => '',
                    "previoustotalcount" => '',
                    "receivedcount" => '',
                    "balancecount" => '',
                    "lessthanthree" => '',
                    "lessthanone" => '',
                    "morethanthree" => '',
                    "total" => ''
                );
            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.officerwise', compact('officedivisions'));
    }

    public function reportOfficeWise(Request $request)
    {


        if(request()->ajax())
        {
            $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->get();


            if($request->year != '' && $request->month != ''){
                if(!empty($officedivisions)){
                    foreach($officedivisions as $item){

                        $dateE = Carbon::createFromFormat('m Y', $request->month.' '.$request->year)->firstOfMonth();
                        $nextmonth = $request->month + 1;
                        $dateEE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth();
                        $previousbalance1 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                            ->count();

                        //get camplaints that closed after searched month
                        $previousbalance2 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $previousbalance3 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $previousbalance = $previousbalance1 + $previousbalance2 + $previousbalance3;
                        
                        $settledprevious1 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $settledprevious2 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $settledprevious = $settledprevious1+ $settledprevious2;

                        $previoustotalcount = $previousbalance + $settledprevious;

                        $receivedcount = RegisterComplaint::whereYear('register_complaints.created_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.created_at', '=' ,$request->month)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->count();

                        $settledcount1 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $settledcount2 = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $settledcount = $settledcount1 + $settledcount2;

                        $balancecount = $previoustotalcount + $receivedcount - $settledcount;


                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $dateEE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth();
                        
                        $lessthanone1 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateEE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                            ->count();

                        //get camplaints that closed after searched month
                        $lessthanone2 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateEE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $lessthanone3 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateEE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $lessthanone = $lessthanone1 + $lessthanone2 + $lessthanone3;

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $dateE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $lessthanthree1 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                            ->count();

                        //get camplaints that closed after searched month
                        $lessthanthree2 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $lessthanthree3 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $lessthanthree = $lessthanthree1 + $lessthanthree2 + $lessthanthree3;

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $morethanthree1 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                                ->where('created_at', '<' ,$dateS)
                                ->where('register_complaints.complaint_status', '!=', 'Closed')
                                ->where('register_complaints.complaint_status', '!=', 'Tempclosed')
                                ->count();

                        //get camplaints that closed after searched month
                        $morethanthree2 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->where('created_at', '<' ,$dateS)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        //get camplaints that temp closed after searched month
                        $morethanthree3 = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->where('created_at', '<' ,$dateS)
                            ->where('updated_at', '>=', $dateEE)
                            ->where('register_complaints.complaint_status','Tempclosed')
                            ->count();

                        $morethanthree = $morethanthree1 + $morethanthree2 + $morethanthree3;

                        $total = $lessthanone + $lessthanthree + $morethanthree;

                        if(Session::get('applocale') == 'ta'){
                            $office_name = $item->office_name_tam;
                        } else if (Session::get('applocale') == 'si'){
                            $office_name = $item->office_name_sin;
                        } else {
                            $office_name = $item->office_name_en;
                        }

                        $data[] = array(
                            "office" => $office_name,
                            "previousbalance" => $previousbalance,
                            "settledprevious" => $settledprevious,
                            "settledcount" => $settledcount,
                            "previoustotalcount" => $previoustotalcount,
                            "receivedcount" => $receivedcount,
                            "balancecount" => $balancecount,
                            "lessthanthree" => $lessthanthree,
                            "lessthanone" => $lessthanone,
                            "morethanthree" => $morethanthree,
                            "total" => $total
                        );

                    }

                }
            } else {
                $data[] = array(
                    "office" => '',
                    "previousbalance" => '',
                    "settledprevious" => '',
                    "settledcount" => '',
                    "previoustotalcount" => '',
                    "receivedcount" => '',
                    "balancecount" => '',
                    "lessthanthree" => '',
                    "lessthanone" => '',
                    "morethanthree" => '',
                    "total" => ''
                );
            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.officewise');
    }

    public function getLoUnassignComplaints(Request $request)
    {
        if($request->labour_office != ''){
            $looffice = RegisterComplaint::where('register_complaints.lo_officer_id','=', null)
                ->where('register_complaints.current_office_id', $request->labour_office)
                ->count();
        } else {
            $looffice = RegisterComplaint::where('register_complaints.lo_officer_id','=', null)
                ->count();
        }
        return response()->json($looffice);
    }

    public function reportcomplaintbyperiod(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->get();

        if(request()->ajax())
        {
            if($request->period != '' && $request->labour_office != ''){
                if($request->period == '<01'){
                    $data = RegisterComplaint::whereBetween('register_complaints.created_at', [date('Y-m-d H:i:s', strtotime($date. ' - 1 month')), $date])->where('register_complaints.current_office_id','=', $request->labour_office);
                } else if($request->period == '01>03') {
                    $data = RegisterComplaint::whereBetween('register_complaints.created_at', [date('Y-m-d H:i:s', strtotime($date. ' - 3 month')), date('Y-m-d H:i:s', strtotime($date. ' - 1 month'))])->where('register_complaints.current_office_id','=', $request->labour_office);
                } else if($request->period == '03>06') {
                    $data = RegisterComplaint::whereBetween('register_complaints.created_at', [date('Y-m-d H:i:s', strtotime($date. ' - 6 month')), date('Y-m-d H:i:s', strtotime($date. ' - 3 month'))])->where('register_complaints.current_office_id','=', $request->labour_office);
                } else if($request->period == '06>12') {
                    $data = RegisterComplaint::whereBetween('register_complaints.created_at', [date('Y-m-d H:i:s', strtotime($date. ' - 12 month')), date('Y-m-d H:i:s', strtotime($date. ' - 6 month'))])->where('register_complaints.current_office_id','=', $request->labour_office);
                } else if($request->period == '1<') {
                    $data = RegisterComplaint::where('register_complaints.created_at', '<', date('Y-m-d H:i:s', strtotime($date. ' - 12 month')))->where('register_complaints.current_office_id','=', $request->labour_office);
                } else {

                }

                $data->join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->leftJoin('users', 'users.id', '=', 'register_complaints.lo_officer_id')
                ->select('register_complaints.id', 'register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name', 'users.name as lo_name', 'labour_offices_divisions.office_name_en', 'register_complaints.current_office_id')->get();

            } else if($request->period == '' && $request->labour_office != ''){
                $data = RegisterComplaint::where('register_complaints.current_office_id','=', $request->labour_office);
                $data->join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->leftJoin('users', 'users.id', '=', 'register_complaints.lo_officer_id')
                ->select('register_complaints.id', 'register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name', 'users.name as lo_name', 'labour_offices_divisions.office_name_en', 'register_complaints.current_office_id')->get();
            } else {
                $data = RegisterComplaint::join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                ->leftJoin('users', 'users.id', '=', 'register_complaints.lo_officer_id')
                ->select('register_complaints.id', 'register_complaints.external_ref_no', 'register_complaints.ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_mobile', 'register_complaints.complainant_full_name', 'register_complaints.complain_category', 'register_complaints.complaint_status', 'register_complaints.created_at', 'register_complaints.employer_name', 'users.name as lo_name', 'labour_offices_divisions.office_name_en', 'register_complaints.current_office_id')->get();
            }

            return datatables()->of($data)
            ->editColumn('created_at', function ($request) {
                return $request->created_at->format('Y-m-d'); // human readable format
            })->make(true);
        }
        return view('adminpanel.reportcomplaint.byperiod', compact('officedivisions'));
    }

    public function reportTransferComplaint(Request $request)
    {

        $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
        $to_date = Carbon::parse($request->input('to_date'))->endOfDay();

        if(request()->ajax())
        {
            $complaints = ComplaintHistory::where('status', 'Approved_forward')
                ->orderBy('created_at', 'DESC')
                ->get();

            if(!empty($complaints)){

                if($request->from_date != '' && $request->to_date != ''){
                    $approvedComplaints = ComplaintHistory::join('register_complaints', 'register_complaints.id', '=', 'complaint_histories.complaint_id')
                                ->whereDate('created_at', '>=', $from_date)
                                ->whereDate('created_at', '<=', $to_date)
                                ->where('status','=', 'Approved_forward')
                                ->orderBy('created_at', 'DESC')
                                ->select('complaint_histories.sent_from_office_code', 'complaint_histories.sent_to_office_code', 'complaint_histories.created_at', 'complaint_histories.remark','register_complaints.ref_no', 'register_complaints.external_ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_full_name', 'register_complaints.complainant_mobile', 'register_complaints.employer_name');

                } else if($request->from_date != '' && $request->to_date == ''){
                    $approvedComplaints = ComplaintHistory::join('register_complaints', 'register_complaints.id', '=', 'complaint_histories.complaint_id')
                                ->whereDate('created_at', '>=', $from_date)
                                ->where('status','=', 'Approved_forward')
                                ->orderBy('created_at', 'DESC')
                                ->select('complaint_histories.sent_from_office_code', 'complaint_histories.sent_to_office_code', 'complaint_histories.created_at', 'complaint_histories.remark','register_complaints.ref_no', 'register_complaints.external_ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_full_name', 'register_complaints.complainant_mobile', 'register_complaints.employer_name');

                } else if($request->from_date == '' && $request->to_date != ''){
                    $approvedComplaints = ComplaintHistory::join('register_complaints', 'register_complaints.id', '=', 'complaint_histories.complaint_id')
                                ->whereDate('created_at', '<=', $to_date)
                                ->where('status','=', 'Approved_forward')
                                ->orderBy('created_at', 'DESC')
                                ->select('complaint_histories.sent_from_office_code', 'complaint_histories.sent_to_office_code', 'complaint_histories.created_at', 'complaint_histories.remark','register_complaints.ref_no', 'register_complaints.external_ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_full_name', 'register_complaints.complainant_mobile', 'register_complaints.employer_name');

                } else {
                    $approvedComplaints = ComplaintHistory::join('register_complaints', 'register_complaints.id', '=', 'complaint_histories.complaint_id')
                                ->where('status','=', 'Approved_forward')
                                ->orderBy('created_at', 'DESC')
                                ->select('complaint_histories.sent_from_office_code', 'complaint_histories.sent_to_office_code', 'complaint_histories.created_at', 'complaint_histories.remark','register_complaints.ref_no', 'register_complaints.external_ref_no', 'register_complaints.complainant_identify_no', 'register_complaints.complainant_full_name', 'register_complaints.complainant_mobile', 'register_complaints.employer_name');
                }


            }

            return datatables()->of($approvedComplaints)
            ->editColumn('created_at', function ($request) {
                return $request->created_at->format('Y-m-d'); // human readable format
            })
            ->filterColumn('external_ref_no', function ($query, $keyword) {
                $query->whereRaw('LOWER(register_complaints.external_ref_no) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('ref_no', function ($query, $keyword) {
                $query->whereRaw('LOWER(register_complaints.ref_no) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('complainant_identify_no', function ($query, $keyword) {
                $query->whereRaw('LOWER(register_complaints.complainant_identify_no) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('complainant_full_name', function ($query, $keyword) {
                $query->whereRaw('LOWER(register_complaints.complainant_full_name) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('complainant_mobile', function ($query, $keyword) {
                $query->whereRaw('LOWER(register_complaints.complainant_mobile) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('employer_name', function ($query, $keyword) {
                $query->whereRaw('LOWER(register_complaints.employer_name) LIKE ?', ["%{$keyword}%"]);
            })
            ->make(true);

        }
        return view('adminpanel.reportcomplaint.transferComplaint');
    }

    public function reportCategoryWise(Request $request)
    {

        $complaintcategories = Complain_Category::where('status', 'Y')
                    ->orderBy('order', 'ASC')
                    ->get();

        if(request()->ajax())
        {
            $complaintcategories = Complain_Category::where('status', 'Y')
                    ->orderBy('order', 'ASC')
                    ->get();

            $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
            $to_date = Carbon::parse($request->input('to_date'))->endOfDay();


            if(!empty($complaintcategories)) {


                foreach($complaintcategories as $key => $item){

                    if($request->from_date != '' && $request->to_date != '') {

                    $complaintcount[$key] = RegisterComplaint::whereIn('complain_category', [$item->id])->whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                                            ->count();

                    $totalcompaint = $complaintcount[$key];

                    if(Session::get('applocale') == 'ta'){
                        $category_name = $item->category_name_ta;
                    } else if (Session::get('applocale') == 'si'){
                        $category_name = $item->category_name_si;
                    } else {
                        $category_name = $item->category_name_en;
                    }

                    $data[] = array(
                        "office" => $category_name,
                        "totalcount" => $totalcompaint
                    );

                } else if($request->from_date != '' && $request->to_date == '') {

                    $complaintcount[$key] = RegisterComplaint::whereIn('complain_category', [$item->id])->whereDate('register_complaints.created_at', '>=', $from_date)
                                            ->count();

                    $totalcompaint = $complaintcount[$key];

                    if(Session::get('applocale') == 'ta'){
                        $category_name = $item->category_name_ta;
                    } else if (Session::get('applocale') == 'si'){
                        $category_name = $item->category_name_si;
                    } else {
                        $category_name = $item->category_name_en;
                    }

                    $data[] = array(
                        "office" => $category_name,
                        "totalcount" => $totalcompaint
                    );

                }else{

                    $complaintcount[$key] = RegisterComplaint::whereIn('complain_category', [$item->id])
                    ->count();

                    $totalcompaint = $complaintcount[$key];

                    if(Session::get('applocale') == 'ta'){
                        $category_name = $item->category_name_ta;
                    } else if (Session::get('applocale') == 'si'){
                        $category_name = $item->category_name_si;
                    } else {
                        $category_name = $item->category_name_en;
                    }

                    $data[] = array(
                        "office" => $category_name,
                        "totalcount" => $totalcompaint
                    );
                }

                }
            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.categorywise', compact('complaintcategories'));
    }

    public function reportPerformance(Request $request)
    {

        $labouroffice = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->orderBy('office_name_en', 'ASC')
                    ->get();

        if(request()->ajax())
        {
            $labouroffice = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->orderBy('office_name_en', 'ASC')
                    ->get();

            $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
            $to_date = Carbon::parse($request->input('to_date'))->endOfDay();


            if(!empty($labouroffice)){

                // $count = 0;
                foreach($labouroffice as $key => $item){
                    // $count++;
                    $receivedcomplaintcount[$key] = RegisterComplaint::where('current_office_id', $item->id)
                                                                    ->count();


                    $actionpendingcomplaintcount[$key] = RegisterComplaint::where('current_office_id', $item->id)
                                                                    ->where('action_type', 'Pending')
                                                                    ->count();

                    $approvependingcomplaintcount[$key] = RegisterComplaint::where('current_office_id', $item->id)
                                                                    ->where('action_type', 'Pending_approve')
                                                                    ->count();

                    $legalpendingcomplaintcount[$key] = RegisterComplaint::where('current_office_id', $item->id)
                                                                    ->where('action_type', 'Pending_legal')
                                                                    ->count();

                    if($receivedcomplaintcount[$key]>0 && $actionpendingcomplaintcount[$key] > 0) {

                        $totalpendingcount = $actionpendingcomplaintcount[$key] + $approvependingcomplaintcount[$key] + $legalpendingcomplaintcount[$key];

                        $performanceperentage = (($totalpendingcount/$receivedcomplaintcount[$key]) * 100);

                        $finalCalculation = round(100 - $performanceperentage);

                        $finalCalculation = $finalCalculation;

                        // $arrayperformancevalue[] = array('office_id' => $item->id, 'precentage' => $performanceperentage);

                        // dd($arrayperformancevalue);

                    } else {

                        $finalCalculation = 0;

                    }

                    if(Session::get('applocale') == 'ta'){
                        $office_name = $item->office_name_tam;
                    } else if (Session::get('applocale') == 'si'){
                        $office_name = $item->office_name_sin;
                    } else {
                        $office_name = $item->office_name_en;
                    }

                    // dd($finalCalculation);
                    $data[] = array(
                        // "id" => $count,
                        'percentage' => $finalCalculation,
                        "office" => $office_name,
                        "rank" => ""

                    );

                }

            }


            array_multisort(array_column( $data, 'percentage' ), SORT_DESC, $data);
            // rsort($data);

            // $array = collect($data)->sortBy('percentage')->reverse()->toArray();

            // $array = collect($data)->sortBy('percentage')->toArray();

            $arrlength = count($data);
            $rank = 1;
            $prev_rank = $rank;

            // for($x = 0; $x < $arrlength; $x++) {
            $count = 0;
            foreach($data as $key => $arr) {

                if ($count==0) {
                    //  echo $array[$x]."- Rank".($rank);

                     $data[$key]['rank'] = $rank;


                }
               elseif ($data[$count]['percentage'] != $data[$count-1]['percentage']) {

                    $rank++;
                    $prev_rank = $rank;
                    // echo $array[$x]."- Rank".($rank);

                    $data[$key]['rank'] = $rank;
               }

               else {
                    $rank++;

                    $data[$key]['rank'] = $prev_rank;
                    // echo $array[$x]."- Rank".($prev_rank);
                }

                $count++;

            }


            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.performance', compact('labouroffice'));
    }

    public function reporttimeanalysis(Request $request)
    {
        $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
                    ->orderBy('office_name_en', 'ASC')
                    ->get();

        if(request()->ajax())
        {

            if($request->labour_office != '' && $request->labour_office != NULL){
                $complaints = RegisterComplaint::select('id', 'ref_no', 'external_ref_no', 'current_office_id', 'created_at')->where('current_office_id', $request->labour_office)->orderBy('created_at', 'ASC')->get();
            } else {
                $complaints = RegisterComplaint::select('id', 'ref_no', 'external_ref_no', 'current_office_id', 'created_at')->where('current_office_id', '!=' , NULL)->orderBy('created_at', 'ASC')->get();
            }

            if(!empty($complaints)){

                foreach($complaints as $key => $item){

                    $request_assign_lo_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                        ->where('complaint_histories.status', '=', 'Request_assign_lo')
                        ->where('complaint_histories.complaint_id', '=', $item->id)
                        ->orderBy('complaint_histories.created_at', 'DESC')
                        ->select('complaint_histories.created_at')
                        ->first();

                    if(!empty($request_assign_lo_date)){
                        $diff1 = abs(strtotime($request_assign_lo_date->created_at) - strtotime($item->created_at)); 
                        $diff1_days = round($diff1/ (60*60*24));

                        $approved_assign_lo_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                            ->where('complaint_histories.status', '=', 'Approved_assign_lo')
                            ->where('complaint_histories.complaint_id', '=', $item->id)
                            ->orderBy('complaint_histories.created_at', 'DESC')
                            ->select('complaint_histories.created_at')
                            ->first();

                            if(!empty($approved_assign_lo_date)){
                                $diff2 = abs(strtotime($approved_assign_lo_date->created_at) - strtotime($request_assign_lo_date->created_at));
                                $diff2_days = round($diff2/ (60*60*24));

                                $create_event_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                    ->where('complaint_histories.status', '=', 'Create_event')
                                    ->where('complaint_histories.complaint_id', '=', $item->id)
                                    ->orderBy('complaint_histories.created_at', 'ASC')
                                    ->select('complaint_histories.created_at')
                                    ->first();

                                if(!empty($create_event_date)){
                                    $diff3 = abs(strtotime($create_event_date->created_at) - strtotime($approved_assign_lo_date->created_at));  
                                    $diff3_days = round($diff3/ (60*60*24));
                                    
                                } else {
                                    $diff3_days = '';
                                }

                                $request_closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                    ->where('complaint_histories.status', '=', 'Request_approve_close')
                                    ->where('complaint_histories.complaint_id', '=', $item->id)
                                    ->orderBy('complaint_histories.created_at', 'DESC')
                                    ->select('complaint_histories.created_at')
                                    ->first();

                                $request_temporary_closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                    ->where('complaint_histories.status', '=', 'Request_approve_temp_close')
                                    ->where('complaint_histories.complaint_id', '=', $item->id)
                                    ->orderBy('complaint_histories.created_at', 'DESC')
                                    ->select('complaint_histories.created_at')
                                    ->first();

                                if(!empty($request_closed_date)){
                                    $diff4 = abs(strtotime($request_closed_date->created_at) - strtotime($item->created_at));  
                                    $diff4_days = round($diff4/ (60*60*24));

                                    $closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                        ->where('complaint_histories.status', '=', 'Closed')
                                        ->where('complaint_histories.complaint_id', '=', $item->id)
                                        ->orderBy('complaint_histories.created_at', 'DESC')
                                        ->select('complaint_histories.created_at')
                                        ->first();

                                    if(!empty($closed_date)){
                                        $diff5 = abs(strtotime($closed_date->created_at) - strtotime($request_closed_date->created_at));  
                                        $diff5_days = round($diff5/ (60*60*24));
                                    } else {
                                        $diff5_days = '';
                                    }

                                } else if (!empty($request_temporary_closed_date)){
                                    $diff4 = abs(strtotime($request_temporary_closed_date->created_at) - strtotime($item->created_at));  
                                    $diff4_days = round($diff4/ (60*60*24));

                                    $temporary_closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                        ->where('complaint_histories.status', '=', 'Tempclosed')
                                        ->where('complaint_histories.complaint_id', '=', $item->id)
                                        ->orderBy('complaint_histories.created_at', 'DESC')
                                        ->select('complaint_histories.created_at')
                                        ->first();

                                    if(!empty($temporary_closed_date)){
                                        $diff5 = abs(strtotime($temporary_closed_date->created_at) - strtotime($request_temporary_closed_date->created_at));  
                                        $diff5_days = round($diff5/ (60*60*24));
                                    } else {
                                        $diff5_days = '';
                                    }

                                } else {
                                    $diff4_days = '';
                                    $diff5_days = '';
                                }

                            } else {
                                $diff2_days = '';
                                $diff3_days = '';
                                $diff4_days = '';
                                $diff5_days = '';
                            }

                    } else {
                        $diff1_days = '';
                        $diff2_days = '';
                        $diff3_days = '';

                        $request_closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                            ->where('complaint_histories.status', '=', 'Request_approve_close')
                            ->where('complaint_histories.complaint_id', '=', $item->id)
                            ->orderBy('complaint_histories.created_at', 'DESC')
                            ->select('complaint_histories.created_at')
                            ->first();

                        $request_temporary_closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                            ->where('complaint_histories.status', '=', 'Request_approve_temp_close')
                            ->where('complaint_histories.complaint_id', '=', $item->id)
                            ->orderBy('complaint_histories.created_at', 'DESC')
                            ->select('complaint_histories.created_at')
                            ->first();

                        if(!empty($request_closed_date)){
                            $diff4 = abs(strtotime($request_closed_date->created_at) - strtotime($item->created_at));  
                            $diff4_days = round($diff4/ (60*60*24));

                            $closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                ->where('complaint_histories.status', '=', 'Closed')
                                ->where('complaint_histories.complaint_id', '=', $item->id)
                                ->orderBy('complaint_histories.created_at', 'DESC')
                                ->select('complaint_histories.created_at')
                                ->first();

                            if(!empty($closed_date)){
                                $diff5 = abs(strtotime($closed_date->created_at) - strtotime($request_closed_date->created_at));  
                                $diff5_days = round($diff5/ (60*60*24));
                            } else {
                                $diff5_days = '';
                            }
                            
                        } else if (!empty($request_temporary_closed_date)){
                            $diff4 = abs(strtotime($request_temporary_closed_date->created_at) - strtotime($item->created_at));  
                            $diff4_days = round($diff4/ (60*60*24));

                            $temporary_closed_date = RegisterComplaint::join('complaint_histories', 'complaint_histories.complaint_id', '=', 'register_complaints.id')
                                ->where('complaint_histories.status', '=', 'Tempclosed')
                                ->where('complaint_histories.complaint_id', '=', $item->id)
                                ->orderBy('complaint_histories.created_at', 'DESC')
                                ->select('complaint_histories.created_at')
                                ->first();

                            if(!empty($temporary_closed_date)){
                                $diff5 = abs(strtotime($temporary_closed_date->created_at) - strtotime($request_temporary_closed_date->created_at));  
                                $diff5_days = round($diff5/ (60*60*24));
                            } else {
                                $diff5_days = '';
                            }

                        } else {
                            $diff4_days = '';
                            $diff5_days = '';
                        }
                    }

                    $data[] = array(
                        "complaint_no" => $item->ref_no,
                        "diff1_days" => $diff1_days,
                        "diff2_days" => $diff2_days,
                        "diff3_days" => $diff3_days,
                        "diff4_days" => $diff4_days,
                        "diff5_days" => $diff5_days
                    );

                }
            } else {
                
                $data[] = array(
                    "complaint_no" => '',
                    "diff1_days" => '',
                    "diff2_days" => '',
                    "diff3_days" => '',
                    "diff4_days" => '',
                    "diff5_days" => ''
                );

            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.timeanalysis', compact('officedivisions'));
    }
}
