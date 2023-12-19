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
    }

    public function reportcomplaint(Request $request)
    {
        $officedivisions = LabourOfficeDivision::where('status', 'Y')
                    ->where('is_delete', '0')
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
                $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '>=', $to_date)->where('register_complaints.current_office_id','=', $request->labour_office)->count();
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

            } else {
                $totalcount = RegisterComplaint::count();
                $closedcount = RegisterComplaint::where('complaint_status', 'Closed')->count();
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

        $loOfficers = User::role(['Labour Officer','SLO'])
                ->where('office_id', Auth::user()->office_id)
                ->where('is_delete', 0)
                ->where('status', 'Y')
                ->get();

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
        $complaintstatus = RegisterComplaint::where('register_complaints.is_delete', '0')
            ->join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
            ->select('register_complaints.action_type','labour_offices_divisions.office_name_en', DB::raw('count(*) as total'))
            ->groupBy('labour_offices_divisions.office_name_en','register_complaints.action_type')
            ->orderBy('labour_offices_divisions.office_name_en', 'ASC')
            ->orderBy('register_complaints.action_type', 'ASC')
            ->get();

        if(request()->ajax())
        {
            $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
            $to_date = Carbon::parse($request->input('to_date'))->endOfDay();
            
            if($request->from_date != '' && $request->to_date != ''){
                $complaintstatus = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)->whereDate('register_complaints.created_at', '<=', $to_date)
                    ->where('register_complaints.is_delete', '0')
                    ->join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                    ->select('register_complaints.action_type','labour_offices_divisions.office_name_en', DB::raw('count(*) as total'))
                    ->groupBy('labour_offices_divisions.office_name_en','register_complaints.action_type')
                    ->orderBy('labour_offices_divisions.office_name_en', 'ASC')
                    ->orderBy('register_complaints.action_type', 'ASC')
                    ->get();
            }
            else if($request->from_date != '' && $request->to_date == ''){
                $complaintstatus = RegisterComplaint::whereDate('register_complaints.created_at', '>=', $from_date)
                    ->where('register_complaints.is_delete', '0')
                    ->join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                    ->select('register_complaints.action_type','labour_offices_divisions.office_name_en', DB::raw('count(*) as total'))
                    ->groupBy('labour_offices_divisions.office_name_en','register_complaints.action_type')
                    ->orderBy('labour_offices_divisions.office_name_en', 'ASC')
                    ->orderBy('register_complaints.action_type', 'ASC')
                    ->get();
            }
            else{
                $complaintstatus = RegisterComplaint::where('register_complaints.is_delete', '0')
                    ->join('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'register_complaints.current_office_id')
                    ->select('register_complaints.action_type','labour_offices_divisions.office_name_en', DB::raw('count(*) as total'))
                    ->groupBy('labour_offices_divisions.office_name_en','register_complaints.action_type')
                    ->orderBy('labour_offices_divisions.office_name_en', 'ASC')
                    ->orderBy('register_complaints.action_type', 'ASC')
                    ->get();
            }

            if(!empty($complaintstatus)){

                $rewriteKeys = array('Tempclosed' => 'Temporary closed', 'Closed' => 'Closed', 'Waiting' => 'Writ/Revision/Appeal', 
                    'Pending_plaint_charge_sheet' => 'Plaint & charge sheet', 'Pending_legal' => 'Legal Certification', 'Pending_recovery' => 'Recovery stage', 'Pending' => 'Action Pending',
                    'Ongoing' => 'Complaint processing', 'Pending_approve' => 'Pending Approval');

                foreach($complaintstatus as $item){

                    $data[] = array(
                        "office" => $item->office_name_en,
                        "act" => $rewriteKeys[$item->action_type],
                        // "act" => $item->action_type,
                        "totalcount" => $item->total
                    );

                }
            }

            return datatables()->of($data)->make(true);
        }
        return view('adminpanel.reportcomplaint.eachact', compact('complaintstatus'));
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
            if($request->labour_office != '') {
                $loOfficers = User::role(13)
                ->leftJoin('labour_offices_divisions', 'labour_offices_divisions.id', '=', 'users.office_id')
                ->where('users.office_id','=', $request->labour_office)
                ->where('users.status', 'Y')
                ->where('users.is_delete',0)
                ->select('users.id','users.name','users.office_id','labour_offices_divisions.office_name_en','labour_offices_divisions.office_name_tam','labour_offices_divisions.office_name_sin')
                ->get();

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
                        $previousbalance = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                        ->where('register_complaints.created_at', '<' ,$dateE)
                        ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->count();

                        $settledprevious = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $previoustotalcount = $previousbalance + $settledprevious;

                        $receivedcount = RegisterComplaint::whereYear('register_complaints.created_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.created_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->count();

                            $settledcount = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.lo_officer_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $balancecount = $previousbalance + $receivedcount - $settledcount;


                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $dateE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth();
                        $lessthanone = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->count();

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $dateE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $lessthanthree = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->count();

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $morethanthree = RegisterComplaint::where('register_complaints.lo_officer_id','=', $item->id)
                                ->where('created_at', '<' ,$dateS)
                                ->where('register_complaints.complaint_status', '!=', 'Closed')
                                ->count();

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
                        $previousbalance = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->count();

                        $settledprevious = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.created_at', '<' ,$dateE)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $previoustotalcount = $previousbalance + $settledprevious;

                        $receivedcount = RegisterComplaint::whereYear('register_complaints.created_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.created_at', '=' ,$request->month)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->count();

                            $settledcount = RegisterComplaint::whereYear('register_complaints.updated_at', '=' ,$request->year)
                            ->whereMonth('register_complaints.updated_at', '=' ,$request->month)
                            ->where('register_complaints.current_office_id','=', $item->id)
                            ->where('register_complaints.complaint_status','Closed')
                            ->count();

                        $balancecount = $previousbalance + $receivedcount - $settledcount;


                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $dateE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth();
                        $lessthanone = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->count();

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $dateE = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(1);
                        $lessthanthree = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                            ->whereBetween('created_at',[$dateS,$dateE])
                            ->where('register_complaints.complaint_status', '!=', 'Closed')
                            ->count();

                        $nextmonth = $request->month + 1;
                        $dateS = Carbon::createFromFormat('m Y', $nextmonth.' '.$request->year)->firstOfMonth()->subMonth(3);
                        $morethanthree = RegisterComplaint::where('register_complaints.current_office_id','=', $item->id)
                                ->where('created_at', '<' ,$dateS)
                                ->where('register_complaints.complaint_status', '!=', 'Closed')
                                ->count();

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
}
