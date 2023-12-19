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
use App\Models\BusinessNature;
use App\Models\City;
use App\Models\ComplaintCategoryDetail;
use App\Models\LabourOfficeDivision;
use App\Models\User;
use App\Models\ComplaintStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\ForwardType;
use App\Models\GratuityDetails;
use App\Models\RegisterComplaintCopy;
use Carbon\Carbon;
use App\Models\MailTemplate;
use App\Library\MobitelSms;
use App\Models\SmsTemplate;
use App\Models\LabourOfficeCityDetail;
use App\Models\ComplaintRemark;
use Illuminate\Support\Facades\DB;
use Session;

class ActionPendingListController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:action-pending-list|complaint-report-upload|complaint-modify|complaint-view|complaint-status-history|complaint-action', ['only' => ['list']]);
        //$this->middleware('permission:action-complaint-list|recovery-pending-list', ['only' => ['list']]);
        $this->middleware('permission:complaint-report-upload', ['only' => ['upload', 'uploadfiles']]);
        $this->middleware('permission:complaint-modify', ['only' => ['edit', 'update', 'deletedocument', 'deleteofficer']]);
        $this->middleware('permission:complaint-view', ['only' => ['view']]);
        $this->middleware('permission:complaint-status-history', ['only' => ['complaintStatus']]);
        $this->middleware('permission:complaint-action', ['only' => ['complaintAction']]);
        $this->middleware('permission:sent-for-approval-list', ['only' => ['sentApprovalList']]);
    }

    public function list(Request $request)
    {
        $office_id = Auth::user()->office_id;

        $user_id = Auth::user()->id;

        $userrole = Auth::user()->roles->pluck('name')->first();

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
                ->where('action_type', '=', 'Pending')
                // ->orWhere('complaint_status','=','Request - Issue Certification')
                // ->orWhere('complaint_status','=','Approved - Issue Certification')
                // ->orWhere('complaint_status','=','Request - Create Plaint & Chart Sheet')
                // ->orWhere('complaint_status','=','Created Plaint & Chart Sheet')
                ->where('current_office_id', $office_id);
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
                ->rawColumns(['status', 'action', 'created_at', 'upload', 'modify', 'view', 'calculation', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.actionPendingList', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount, 'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

    public function upload($id)
    {
        $complainID = decrypt($id);
        $data = RegisterComplaint::find($complainID);
        return view('adminpanel.complaint.upload_document', ['data' => $data]);
    }

    public function uploadfiles(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction


        $validatedData = $request->validate([
            'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
        ]);
        //dd($request->complaint_id);
        $data = RegisterComplaint::find($request->complaint_id);

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();

                $insert[$key]['ref_no'] = $request->complaint_id;
                $insert[$key]['file_name'] = $path;
                $insert[$key]['description'] = $name;
            }
            ComplaintDocument::insert($insert);
            $id = \DB::getPdo()->lastInsertId();

            \LogActivity::addToLog('New File('.$id.') uploaded to complaint number '.$data->eternal_ref_no.'.');
        }
        DB::commit();
        return redirect()->to($request->previous_url)
            ->with('success', 'File upload successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function view($id)
    {
        if (strlen($id) < 10) {
            $complainID = $id;
        } else {
            $complainID = decrypt($id);
        }

        $data = RegisterComplaint::with('provinces', 'districts', 'cities', 'establishments', 'labouroffices', 'businessnatures')->find($complainID);

        $complaintdocuments = ComplaintDocument::where('ref_no', $complainID)->get();

        $complaintstatusdetails = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'desc')->get();

        $complaintcategorydetails = ComplaintCategoryDetail::with('complaintcategories')->where('complaint_id', $complainID)->get();

        // dd($complaintcategorydetails);

        //dd($complaintstatusdetails);

        return view('adminpanel.complaint.view', ['data' => $data, 'complaintdocuments' => $complaintdocuments, 'complaintstatusdetails' => $complaintstatusdetails, 'complaintcategorydetails' => $complaintcategorydetails]);
    }

    public function edit($id)
    {
        $complaintID = decrypt($id);

        $data = RegisterComplaint::find($complaintID);
        $province_id = $data->province_id;
        //DB::enableQueryLog();
        $unionofficers = UnionOfficerDetail::where('ref_id', $complaintID)->get();
        //$query = DB::getQueryLog();
       // print_r($query);
       // exit();
      //  print_r($unionofficers); exit();
        $provinces = Province::where('status', 'Y')->where('is_delete', '0')->orderBy('province_name_en', 'ASC')->get();
        $districts = District::where('status', 'Y')->where('is_delete', '0')->where('province_id',$province_id)->orderBy('district_name_en', 'ASC')->get();
        $establishmenttypes = EstablishmentType::where('status', 'Y')->where('is_delete', '0')->orderBy('establishment_name_en', 'ASC')->get();
        $officedivisions = LabourOfficeDivision::where('status', 'Y')->where('is_delete', '0')->get();
        $complaintdocuments = ComplaintDocument::where('ref_no', $complaintID)->get();
        $complaincategories = Complain_Category::where('status', 'Y')->orderBy('order', 'ASC')->get();
        $businessnatures = BusinessNature::where('status', 'Y')->where('is_delete', '0')->orderBy('business_nature_en', 'ASC')->get();

        $office_id = Auth::user()->office_id;
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

        $zone = LabourOfficeDivision::where('status', 'Y')
            ->where('is_delete', '0')
            ->where('office_type_id', '3')
            ->orderBy('office_name_en', 'ASC')
            ->get();

            // dd($zone);

        $categorydetails = ComplaintCategoryDetail::select('category_id', 'id')->where('complaint_id', $complaintID)->get();

       // $cities = City::orderBy('city_name_en', 'ASC')->get();

        $office_id = Auth::user()->office_id;
        $cities = LabourOfficeCityDetail::Join('cities', 'cities.id', '=', 'labour_office_city_details.city_id')
                        ->where('cities.status','Y')
                        ->where('labour_office_city_details.office_id',$office_id)
                        ->where('cities.is_delete','0')
                        ->orderBy('cities.city_name_en', 'ASC')
                        ->get();

        // dd($cities);


        // $categories = RegisterComplaint::select('complain_category')->where('id', $complaintID)->get();
        // $catarr = explode(',', $categories);

        // dd($unionofficers);

        return view('adminpanel.complaint.edit', ['data' => $data, 'unionofficers' => $unionofficers, 'provinces' => $provinces, 'districts' => $districts, 'establishmenttypes' => $establishmenttypes, 'officedivisions' => $officedivisions, 'complaintdocuments' => $complaintdocuments, 'complaincategories' => $complaincategories, 'pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount,'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'categorydetails' => $categorydetails, 'zone' => $zone, 'cities' => $cities, 'businessnatures' => $businessnatures]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction
        $request->validate([
            'ref_no' => 'required',
            // 'complainant_identify_no' => 'required',
            'complainant_f_name' => 'required',
            'complainant_l_name' => 'required',
            // 'complainant_mobile' => 'required',
            'employer_name' => 'required',
            'employer_address' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
        ]);

        if($request->complainant_mobile != "") {
            $request->validate([
                'complainant_mobile' => 'min:10|max:15',
            ]);
        }
        if($request->complainant_tel != "") {
            $request->validate([
                'complainant_tel' => 'min:10|max:15',
            ]);
        }
        if($request->employer_tel != "") {
            $request->validate([
                'employer_tel' => 'min:10|max:15',
            ]);
        }
        if($request->current_employer_tel != "") {
            $request->validate([
                'current_employer_tel' => 'min:10|max:15',
            ]);
        }

        // dd($request->id);
        $office_id = Auth::user()->office_id;
        $complaint =  RegisterComplaint::find($request->complaint_id);
        $complaint->comp_type = $request->comp_type;
        $complaint->pref_lang = $request->pref_lang;
        $complaint->ref_no = $request->ref_no.$request->ref_no1.$request->ref_no2.$request->ref_no3;
        $complaint->complainant_identify_no = $request->complainant_identify_no;
        $complaint->title = $request->title;

        if($request->pref_lang == "SI") {
            $complaint->complainant_f_name_si = $request->complainant_f_name;
            $complaint->complainant_l_name_si = $request->complainant_l_name;
        } else if($request->pref_lang == "TA") {
            $complaint->complainant_f_name_ta = $request->complainant_f_name;
            $complaint->complainant_l_name_ta = $request->complainant_l_name;
        } else {
            $complaint->complainant_f_name = $request->complainant_f_name;
            $complaint->complainant_l_name = $request->complainant_l_name;
        }

        if($request->comp_type == "U"){
            $complaint->complainant_full_name = $request->complainant_f_name.' '.$request->complainant_l_name;
        } else {
            $complaint->complainant_full_name = $request->complainant_full_name;
        }

        $complaint->complainant_dob = $request->complainant_dob;
        $complaint->complainant_gender = $request->complainant_gender;
        $complaint->nationality = $request->nationality;
        $complaint->complainant_email = $request->complainant_email;
        $complaint->complainant_mobile = $request->complainant_mobile;
        $complaint->complainant_tel = $request->complainant_tel;
        $complaint->complainant_address = $request->complainant_address;
        $complaint->union_name = $request->union_name;
        $complaint->union_address = $request->union_address;
        $complaint->employer_name = $request->employer_name;
        $complaint->employer_address = $request->employer_address;
        $complaint->province_id = $request->province_id;
        $complaint->district_id = $request->district_id;
        $complaint->employer_tel = $request->employer_tel;
        $complaint->business_nature_id = $request->business_nature;
        $complaint->establishment_type_id = $request->establishment_type_id;
        $complaint->establishment_reg_no = $request->establishment_reg_no;
        $complaint->employer_no = $request->employer_no;
        $complaint->ppe_no = $request->ppe_no;
        $complaint->epf_no = $request->epf_no;
        $complaint->employee_mem_no = $request->employee_mem_no;
        $complaint->employee_no = $request->employee_no;
        $complaint->designation = $request->designation;
        $complaint->join_date = $request->join_date;
        $complaint->terminate_date = $request->terminate_date;
        $complaint->last_sal_date = $request->last_sal_date;
        // $complaint->basic_sal = $request->basic_sal;
        // $complaint->allowance = $request->allowance;
        $complaint->submitted_office = $request->submitted_office;
        $complaint->submitted_date = $request->submitted_date;
        $complaint->case_no = $request->case_no;
        $complaint->received_relief = $request->received_relief;
        $complaint->is_available = $request->is_available;
        $complaint->complain_purpose = $request->complain_purpose;
        $complaint->current_employer_name = $request->current_employer_name;
        $complaint->current_employer_address = $request->current_employer_address;
        $complaint->current_employer_tel = $request->current_employer_tel;
        $complaint->current_office_id = $office_id;

        $allowance = $request->allowance;
        $basicsal = $request->basic_sal;
        $complaint->worked_employees = $request->worked_employees;

        $floatallowance = str_replace(',', '', $allowance);
        $floatbasicsal = str_replace(',', '', $basicsal);

        if(!empty($floatbasicsal)) {
            $complaint->basic_sal = $floatbasicsal;
        }

        if(!empty($floatallowance)) {
            $complaint->allowance = $floatallowance;
        }
        $complaint->save();
        $id = $complaint->id;



        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' updated('.$id.').');

        $validatedData = $request->validate([
            // 'files' => 'required',
            'files.*' => 'mimes:csv,txt,xlx,xls,pdf,jpg,jpeg,docx,mp3,png,mp4,mov,mkv'
        ]);

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();

                $ref_no = $complaint->id;
                $insert[$key]['ref_no'] = $ref_no;
                $insert[$key]['file_name'] = $path;
                $insert[$key]['description'] = $name;
            }
            ComplaintDocument::insert($insert);
            $id = \DB::getPdo()->lastInsertId();

            \LogActivity::addToLog('New File('.$id.') uploaded to complaint number '.$complaint->external_ref_no.'.');
        }

        if($request->comp_type == "U" && $request->union_officer_name != "" && $request->union_officer_name[0] != null) {
            // dd($request->union_officer_name);
            $count = count($request->union_officer_name) - 1;

            for ($i = 0; $i < $count; $i++) {

                $unionofficerdetail = new UnionOfficerDetail();
                $unionofficerdetail->ref_id = $complaint->id;
                $unionofficerdetail->officer_name = $request->union_officer_name[$i];
                $unionofficerdetail->officer_address = $request->union_officer_address[$i];
                $unionofficerdetail->save();
                $id = $unionofficerdetail->id;

                \LogActivity::addToLog('New Union('.$id.') added to complaint number '.$complaint->external_ref_no.'.');
            }
        }

        // $complaintcategory =  ComplaintCategoryDetail::find($request->complaint_id);

        $countcat = count($request->complain_category_id);

        ComplaintCategoryDetail::where('complaint_id', $request->complaint_id)->delete();

        // for ($i = 0; $i < $countcat; $i++) {

        //     $complaincategorydetail = ComplaintCategoryDetail::find($request->cat_id);

        //     // dd($complaincategorydetail);
        //     $complaincategorydetail->complaint_id = $request->complaint_id;
        //     $complaincategoryid = $request->complain_category_id[$i];

        //     $categoriesexpdate = Complain_Category::select('expiry_days')->where('id', $complaincategoryid)->first();

        //     $expdayscount = Carbon::today()->addDays($categoriesexpdate->expiry_days);

        //     $expirydate = $expdayscount->toDateString();

        //     $complaincategorydetail->category_id = $request->complain_category_id[$i];

        //     $complaincategorydetail->expiry_date = $expirydate;

        //     $complaincategorydetail->save();
        // }

        for ($i = 0; $i < $countcat; $i++) {

            $complaincategorydetail = new ComplaintCategoryDetail();
            $complaincategorydetail->complaint_id = $complaint->id;
            $complaincategoryid = $request->complain_category_id[$i];

            $categoriesexpdate = Complain_Category::select('expiry_days')->where('id', $complaincategoryid)->first();

            $complaintaddeddate = RegisterComplaint::select('created_at')->where('id', $request->complaint_id)->first();

            // $datefr = Carbon::createFromFormat('Y-m-d', $complaintaddeddate);

            $expdayscount = $complaintaddeddate->created_at->addDays($categoriesexpdate->expiry_days);

                        // dd($expdayscount);

            $expirydate = $expdayscount->toDateString();

            $complaincategorydetail->category_id = $request->complain_category_id[$i];

            $complaincategorydetail->expiry_date = $expirydate;

            $complaincategorydetail->save();
            $id = $complaincategorydetail->id;

            \LogActivity::addToLog('New complaint category detail('.$id.') added to complaint number '.$complaint->external_ref_no.'.');
        }

        DB::commit();
        return redirect()->route('action-pending-list')
            ->with('success', 'Complaint updated successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function deletedocument($id)
    {
        $documents = ComplaintDocument::find($id);
        $documents->delete();
        return redirect()->back()->with('success', 'Document successfully deleted');
    }

    public function deleteofficer($id)
    {
        $officer = UnionOfficerDetail::find($id);
        $officer->delete();
        return redirect()->back()->with('success', 'Union officer successfully deleted');
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

        // $role = Auth::user()->roles->pluck('name','id');

        // $loOfficers = User::get();

        $loOfficers = User::role(['Labour Officer','SLO'])->where('office_id', Auth::user()->office_id)->where('is_delete', 0)->where('status', 'Y')->get();

        $complainhistory = ComplaintHistory::where('complaint_id', $complainID)->orderBy('created_at', 'DESC')->first();

        if($data['complaint_status'] == 'Pending'){
            $status_type = 1;
        } else if($data['action_type'] == 'Ongoing'){
            $status_type = 2;
        } else if($data['action_type'] == 'Pending_recovery'){
            $status_type = 3;
        // } else if($data['action_type'] == 'Pending_legal'){
        //     $status_type = 4;
        // } else if($data['action_type'] == 'Pending_plaint_charge_sheet'){
        //     $status_type = 5;
        // } else if($data['action_type'] == 'Tempclosed'){
        //     $status_type = 6;
        } else if($data['action_type'] == 'Waiting'){
            $status_type = 8;
        } else {
            $status_type = 1;
        }

        if($data['action_type'] == 'Pending'){
            $ftype = ForwardType::where('status', 'Y')
            ->where('is_delete', '0')
            ->whereNotIn('id', array(10,8,7,6,4,2))
            ->orderBy('type_name', 'ASC')
            ->get();
        } else {
            $ftype = ForwardType::where('status', 'Y')
            ->where('is_delete', '0')
            ->whereNotIn('id', array(8,7,6,4,2))
            ->orderBy('type_name', 'ASC')
            ->get();
        }

        $tempcloseFtype = ForwardType::where('status', 'Y')
                                    ->where('is_delete', 0)
                                    ->where('id', 7)
                                    ->first();

        $closeFtype = ForwardType::where('status', 'Y')
                                    ->where('is_delete', 0)
                                    ->where('id', 8)
                                    ->first();


        $complaintstatus = complaintStatus::where('status', 'Y')
                                            ->where('is_delete', '0')
                                            ->where('complaint_status_type_id', $status_type)
                                            ->orderBy('status_en', 'ASC')
                                            ->get();

        $tempclosestatus = complaintStatus::where('status', 'Y')
                                            ->where('is_delete', '0')
                                            ->where('complaint_status_type_id', 6)
                                            ->orderBy('status_en', 'ASC')
                                            ->get();

        $closedstatus = complaintStatus::where('status', 'Y')
                                            ->where('is_delete', '0')
                                            ->where('complaint_status_type_id', 7)
                                            ->orderBy('status_en', 'ASC')
                                            ->get();

        $complaintcat = ComplaintCategoryDetail::select('complain_categories.*')
                    ->where('complaint_id', $complainID)
                    ->join('complain_categories', 'complain_categories.id', 'complaint_category_details.category_id')
                    ->orderBy('complain_categories.category_name_en', 'ASC')
                    ->get();

        //return view('adminpanel.complaint.action', ['data' => $data, 'labourOffice' => $office, 'loOfficers' => $loOfficers, 'forwardTypes' => $ftype, 'complaintstatus' => $complaintstatus, 'complaintcat' => $complaintcat]);

        $remarks = ComplaintRemark::where('is_delete',0)->where('status','Y')->orderBy('remark_en', 'ASC')->get();
        //dd($remarks);
        return view('adminpanel.complaint.action', ['data' => $data, 'labourOffice' => $office, 'loOfficers' => $loOfficers, 'forwardTypes' => $ftype, 'complaintstatus' => $complaintstatus, 'complaintcat' => $complaintcat, 'complainhistory' => $complainhistory, 'remarks' => $remarks, 'tempclosestatus' => $tempclosestatus, 'closedstatus' => $closedstatus, 'tempcloseFtype' => $tempcloseFtype, 'closeFtype' => $closeFtype]);

    }

    public function forward(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'labour_office_id' => 'required',
            'forward_type_id' => 'required'
        ]);
        //dd($request->labour_office_id);
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

        $status_to_revert = null;

        if ($request->forward_type_id == 3) {
            $status = 'Request_legal_certificate';
            $action_type = 'Pending_legal';
        } else if ($request->forward_type_id == 4) {
            $status = 'Create_legal_certificate';
        } else if ($request->forward_type_id == 5) {
            $status = 'Request_plaint_charge_sheet';
            $action_type = 'Pending_plaint_charge_sheet';
        } else if ($request->forward_type_id == 6) {
            $status = 'Create_plaint_and_charge_sheet';
        } else if ($request->forward_type_id == 7) {
            $status = 'Request_approve_temp_close';
            $action_type = 'Pending_approve';
        }else if ($request->forward_type_id == 8) {
            $status = 'Request_approve_close';
            $action_type = 'Pending_approve';
        }else if ($request->forward_type_id == 9) {
            $status = 'Request_recovery';
            $action_type = 'Pending_recovery';
        }else if ($request->forward_type_id == 10) {
            $status = 'Request_appeal';
            $action_type = 'Waiting';
            $status_to_revert = $complaint->action_type.','.$complaint->complaint_status;
        } else if($request->forward_type_id == 2) {
            $status = 'Request_approve_forward';
            $action_type = 'Pending_approve';
        } else {
            $status = 'Forward';
            $action_type = 'Pending';
        }

        $complaint->complaint_status = $status;
        $complaint->action_type = $action_type;
        // $complaint->current_office_id = $request->labour_office_id;
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
        if($request->remark_option == 'Other' || $request->remark_option1 == 'Other' || $request->remark_option2 == 'Other' || $request->remark_option3 == 'Other' || $request->remark_option4 == 'Other' || $request->remark_option5 == 'Other' || $request->remark_option6 == 'Other'){
            $insert['remark'] = $request->remark;
        } else if($request->remark_option != "") {
            $insert['remark'] = $request->remark_option;
        } else if($request->remark_option1 != "") {
            $insert['remark'] = $request->remark_option1;
        } else if($request->remark_option2 != "") {
            $insert['remark'] = $request->remark_option2;
        } else if($request->remark_option3 != "") {
            $insert['remark'] = $request->remark_option3;
        } else if($request->remark_option4 != "") {
            $insert['remark'] = $request->remark_option4;
        } else if($request->remark_option5 != "") {
            $insert['remark'] = $request->remark_option5;
        } else {
            $insert['remark'] = $request->remark_option6;
        }

        if($complaint->pref_lang == "SI") {
            if ($request->forward_type_id == 3) {
                $insert['status_des'] = "නීතිමය සහතිකය $sent_to_office_code කාර්යාලයේදී සකස් කරමින් තිබේ";
            } else if ($request->forward_type_id == 4) {
                $insert['status_des'] = 'Legal certificate created';
            } else if ($request->forward_type_id == 5) {
                $insert['status_des'] = "පැමිණිල්ල හා චෝදනා පත්‍රය  $sent_to_office_code කාර්යාලයේදී සකස්කරමින් තිබේ";
            } else if ($request->forward_type_id == 6) {
                $insert['status_des'] = 'Plaint & chart sheet created';
            } else if ($request->forward_type_id == 7) {
                $insert['status_des'] = "පැමිණිල්ල තාවකාලිකව වැසීම සඳහා අනුමැතිය අපේක්ෂිතය";
            } else if ($request->forward_type_id == 8) {
                $insert['status_des'] = "පැමිණිල්ල වැසීම සඳහා අනුමැතිය අපේක්ෂිතය";
            } else if ($request->forward_type_id == 9) {
                $insert['status_des'] = "යථා තත්වයට පත්කිරීම $sent_to_office_code කාර්යාලයේදී සිදුවෙමින් තිබේ";
            } else if ($request->forward_type_id == 10) {
                $insert['status_des'] = "Writ/revision/appeal waiting at $sent_to_office_code office";
            } else if ($request->forward_type_id == 2) {
                $insert['status_des'] = "පැමිණිල්ල $sent_to_office_code කාර්යාලයට යොමු කිරීම සඳහා අනුමැතිය අපේක්ෂිතය";
            } else {
                $insert['status_des'] = "ඔබේ පැමිණිල්ල $sent_to_office_code කාර්යාලයට යොමුකර තිබේ";
            }
        } else if ($complaint->pref_lang == "TA") {
            if ($request->forward_type_id == 3) {
                $insert['status_des'] = "$sent_to_office_code அலுவலகத்தில் சட்டச் சான்றிதழ் தயார்படுத்தப்படுகின்றது";
            } else if ($request->forward_type_id == 4) {
                $insert['status_des'] = 'Legal certificate created';
            } else if ($request->forward_type_id == 5) {
                $insert['status_des'] = "$sent_to_office_code அலுவலகத்தில் முறைப்பாடு மற்றும் குற்றப்பத்திரிகை தயார்படுத்தப்படுகின்றது";
            } else if ($request->forward_type_id == 6) {
                $insert['status_des'] = 'Plaint & chart sheet created';
            } else if ($request->forward_type_id == 7) {
                $insert['status_des'] = "முறைப்பாட்டை தற்காலிகமாக மூடுவதற்கான ஒப்புதலுக்காக காத்திருப்பு ";
            } else if ($request->forward_type_id == 8) {
                $insert['status_des'] = "முறைப்பாட்டை மூடுவதற்கான ஒப்புதலுக்காக காத்திருப்பு";
            } else if ($request->forward_type_id == 9) {
                $insert['status_des'] = "$sent_to_office_code அலுவலகத்தில் மீட்டெடுக்கும் நடவடிக்கை இடம்பெறுகின்றது";
            } else if ($request->forward_type_id == 10) {
                $insert['status_des'] = "Writ/revision/appeal waiting at $sent_to_office_code office";
            } else if ($request->forward_type_id == 2) {
                $insert['status_des'] = "Approval waiting to forward the complaint to $sent_to_office_code office";
            } else {
                $insert['status_des'] = "உங்களது முறைப்பாடு $sent_to_office_code அலுவலகத்திற்கு அனுப்பப்பட்டுள்ளது";
            }
        } else {
            if ($request->forward_type_id == 3) {
                $insert['status_des'] = "Processing legal certificate at $sent_to_office_code office";
            } else if ($request->forward_type_id == 4) {
                $insert['status_des'] = 'Legal certificate created';
            } else if ($request->forward_type_id == 5) {
                $insert['status_des'] = "Processing plaint & chart sheet at $sent_to_office_code office";
            } else if ($request->forward_type_id == 6) {
                $insert['status_des'] = 'Plaint & chart sheet created';
            } else if ($request->forward_type_id == 7) {
                $insert['status_des'] = "Approval waiting to temporarily close the complaint";
            } else if ($request->forward_type_id == 8) {
                $insert['status_des'] = "Approval waiting to close the complaint";
            } else if ($request->forward_type_id == 9) {
                $insert['status_des'] = "Processing recovery at $sent_to_office_code office";
            } else if ($request->forward_type_id == 10) {
                $insert['status_des'] = "Write/revision/appeal waiting at $sent_to_office_code office";
            } else if ($request->forward_type_id == 2) {
                $insert['status_des'] = "Approval waiting for forward the complaint to $sent_to_office_code office";
            } else {
                $insert['status_des'] = "Your Complaint has forward to $sent_to_office_code office";
            }
        }


        $insert['show_status'] = 'Ext';
        $insert['forward_type_id'] = $request->forward_type_id;
        $insert['user_id'] = Auth::user()->id;
        $insert['complaint_status_id'] = 0;
        if($status_to_revert  != null){
            $insert['status_to_revert'] = $status_to_revert;
        }
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

            \Mail::send('mail.complaint-mail',
                array(
                'ref_no' => $complaint->external_ref_no,
                'date' => $complaint->created_at,
                    'name' => $complainantName,
                    'subject' => $e_sub,
                    'body' => $email_body,
                ), function($message) use ($e_name, $complaint)
            {
                $message->from('cms@labourdept.gov.lk');
                $message->to($complaint->complainant_email)->subject($e_name);
            });

            \EmailLog::addToLog($complaint->complainant_f_name, $complaint->complainant_email, $e_sub, $email_body);
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

                $variableData = [$labourofficedetails->office_name_sin,$complaint->external_ref_no,$complaint->complainant_f_name_ta.' '.$complaint->complainant_l_name];

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

        DB::commit();
        return redirect()->to($request->previous_url)
            ->with('success', 'Complaint forward successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function tempclose(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'complaint_status_id' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if (!empty($labour_office)) {
            $sent_to_office_code = $labour_office->office_code;
        } else {
            $sent_to_office_code = NULL;
        }

        if($complaint->action_type == "Pending_recovery" || $complaint->action_type == "Pending"){
            $complaint->complaint_status = $status = 'Tempclosed';
            $complaint->action_type = $action_type = 'Tempclosed';

            if($complaint->pref_lang == "SI") {
                $status_des = 'පැමිණිල්ල තාවකාලිකව වසා ඇත';
            } else if($complaint->pref_lang == "TA") {
                $status_des = 'முறைப்பாடு தற்காலிகமாக மூடப்பட்டுள்ளது';
            } else {
                $status_des = 'Complaint temporarily closed';
            }
        } else {
            $complaint->complaint_status = $status = 'Request_approve_temp_close';
            $complaint->action_type = $action_type = 'Pending_approve';

            if($complaint->pref_lang == "SI") {
                $status_des = 'පැමිණිල්ල තාවකාලිකව වැසීම සඳහා අනුමැතිය අපේක්ෂිතය';
            } else if($complaint->pref_lang == "TA") {
                $status_des = 'முறைப்பாட்டை தற்காலிகமாக மூடுவதற்கான ஒப்புதலுக்காக காத்திருப்பு';
            } else {
                $status_des = 'Approval waiting to temporarily close the complaint';
            }
        }

        $complaint->current_office_id = Auth::user()->office_id;
        $complaint->save();

        if($action_type == 'Tempclosed') {
            \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' is Tempclosed.');

            $insert['sent_from_office_code'] = NULL;
            $insert['sent_to_office'] = NULL;
            $insert['sent_to_office_code'] = NULL;
            $insert['complaint_status_id'] = $request->complaint_status_id;
        } else {
            \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' requested to close temporarily.');

            $insert['sent_from_office_code'] = $sent_from_office_code;
            $insert['sent_to_office'] = $labour_office->id;
            $insert['sent_to_office_code'] = $sent_to_office_code;
            $insert['complaint_status_id'] = 0;
        }

        $insert['complaint_id'] = $request->complaint_id;
        $insert['sent_from_office'] = Auth::user()->office_id;
        if($request->remark_option4 != null){
            if($request->remark_option4 == 'Other'){
                $insert['remark'] = $request->remark;
            } else {
                $insert['remark'] = $request->remark_option4;
            }
        } else {
            $insert['remark'] = '';
        }
        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['forward_type_id'] = 0;
        $insert['status'] = $status;
        $insert['action_type'] = $action_type;
        $insert['status_des'] = $status_des;
        //dd($insert);
        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        if($action_type == 'Tempclosed') {
            \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status Tempclosed.');
        } else {
            \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status Request_approve_temp_close.');
        }

        $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();

        $officename = LabourOfficeDivision::where('id', $complaintdetails->current_office_id)->first();

        if($request->remark_option4){
            if($request->remark_option4 == 'Other'){
                $remark = $request->remark;
            } else {
                $remark = $request->remark_option4;
            }
        } else {
            $remark = '';
        }


        if($complaint->complainant_email != '' && $action_type == 'Tempclosed'){

            $mailitem = MailTemplate::where('status', 'Y')
                    ->where('is_delete', 0)
                    ->where('id', 3)
                    ->get();
            //dd();exit();
            //\App::setLocale($regdata[0]->pref_lang);

            if($complaint->pref_lang == 'EN'){
                $e_sub = $mailitem[0]->mail_template_name_en;
                // $e_body = $mailitem[0]->body_content_en;
                $e_name = $mailitem[0]->mail_template_name_en;

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

                $complainantname = $complaint->complainant_f_name;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$title,$complaintdetails->current_employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address,$officename->office_name_en,$remark,$complaintdetails->complainant_f_name,$complaintdetails->complainant_address];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

                $email_body = 'Dear'.' '.$complainantname.','.$e_body;

            } else if($complaint->pref_lang == 'SI'){
                $e_sub = $mailitem[0]->mail_template_name_sin;
                // $e_body = $mailitem[0]->body_content_sin;
                $e_name = $mailitem[0]->mail_template_name_sin;

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

                $complainantname = $complaint->complainant_f_name_si;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$title,$complaintdetails->current_employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_si,$officename->office_name_sin,$remark,$complaintdetails->complainant_f_name_si,$complaintdetails->complainant_address_si];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

                $email_body = 'හිතවත්'.' '.$complainantname.','.$e_body;

            } else if($complaint->pref_lang == 'TA'){
                $e_sub = $mailitem[0]->mail_template_name_tam;
                // $e_body = $mailitem[0]->body_content_tam;
                $e_name = $mailitem[0]->mail_template_name_tam;

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

                $complainantname = $complaint->complainant_f_name_ta;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$title,$complaintdetails->current_employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_ta,$officename->office_name_tam,$remark,$complaintdetails->complainant_f_name_ta,$complaintdetails->complainant_address_ta];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

                $email_body = 'அன்பார்ந்த'.' '.$complainantname.','.$e_body;
            }

            $mail_body_content = strip_tags($email_body);

            \Mail::send('mail.complaint-mail',
                array(
                'ref_no' => $complaint->external_ref_no,
                'date' => $complaint->created_at,
                    'name' => $complainantname,
                    'subject' => $e_sub,
                    'body' => $mail_body_content,
                ), function($message) use ($e_name, $complaint)
            {
                $message->from('cms@labourdept.gov.lk');
                $message->to($complaint->complainant_email)->subject($e_name);
            });

            \EmailLog::addToLog($complaint->complainant_f_name, $complaint->complainant_email, $e_sub, $mail_body_content);
        }


        if($complaint->complainant_mobile != '' && $action_type == 'Tempclosed'){

            $smsitem = SmsTemplate::where('status', 'Y')
                ->where('is_delete', 0)
                ->where('id', 3)
                ->get();

            if($request->remark_option4 == 'Other'){
                $remark = $request->remark;
            } else {
                $remark = $request->remark_option4;
            }

            if($complaint->pref_lang == 'EN'){
                $s_sub = $smsitem[0]->sms_template_name_en;
                // $s_body = $smsitem[0]->body_content_en;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[REMARK]','[COMPLAINANTNAME]'];

                $variableData = [$officename->office_name_en,$complaint->external_ref_no,$remark,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

                $sms_body = $s_body;

            } else if($complaint->pref_lang == 'SI'){
                $s_sub = $smsitem[0]->sms_template_name_sin;
                // $s_body = $smsitem[0]->body_content_sin;

                $variables = ['[OFFICENAME]','REFERENCENUMBER','[REMARK]','[COMPLAINANTNAME]'];

                $variableData = [$officename->office_name_sin,$complaint->external_ref_no,$remark,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

                $sms_body = $s_body;

            } else if($complaint->pref_lang == 'TA'){
                $s_sub = $smsitem[0]->sms_template_name_tam;
                // $s_body = $smsitem[0]->body_content_tam;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[REMARK]','[COMPLAINANTNAME]'];

                $variableData = [$officename->office_name_sin,$complaint->external_ref_no,$remark,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                $sms_body = $s_body;
            }

            // $session= MobitelSms::createSession('','esmsusr_uqt','2L@boUr$m$','');
            // MobitelSms::sendMessagesMultiLang($session,'Labour Dept','Dear '.$complaint->complainant_f_name.','.$s_body.' '.$request->remark,array($complaint->complainant_mobile),0);
            // MobitelSms::closeSession($session);

            $mobitelSms = new MobitelSms();
            $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
            $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($complaint->complainant_mobile),0);
            $mobitelSms->closeSession($session);

            \SmsLog::addToLog($complaint->complainant_f_name.' '.$complaint->complainant_l_name, $complaint->complainant_mobile, $sms_body);
        }

        DB::commit();
        return redirect()->route('action-pending-list')
            ->with('success', 'Complaint temporarily closed.');

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

        // $test = $complaint->pref_lang;

        // dd($test);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        $complaintdetails = RegisterComplaint::where('id', $request->complaint_id)->first();

        $officename = LabourOfficeDivision::where('id', $complaintdetails->current_office_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }

        if (!empty($labour_office)) {
            $sent_to_office_code = $labour_office->office_code;
        } else {
            $sent_to_office_code = NULL;
        }

        if($complaint->action_type == "Pending_recovery") {
            $complaint->complaint_status = $status = 'Closed';
            $complaint->action_type = $action_type = 'Closed';

            if($complaint->pref_lang == "SI") {
                $status_des = 'පැමිණිල්ල වසා ඇත';
            } else if($complaint->pref_lang == "TA") {
                $status_des = 'முறைப்பாடு மூடப்பட்டுள்ளது';
            } else {
                $status_des = 'Complaint closed';
            }
        } else {
            $complaint->complaint_status = $status = 'Request_approve_close';
            $complaint->action_type = $action_type = 'Pending_approve';

            if($complaint->pref_lang == "SI") {
                $status_des = 'පැමිණිල්ල වැසීම සඳහා අනුමැතිය අපේක්ෂිතය';
            } else if($complaint->pref_lang == "TA") {
                $status_des = ' முறைப்பாட்டை மூடுவதற்கான ஒப்புதலுக்காக காத்திருப்பு';
            } else {
                $status_des = 'Approval waiting to close the complaint';
            }
        }
        $complaint->current_office_id = Auth::user()->office_id;
        $complaint->save();

        if($action_type == 'Closed') {
            \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' is Closed.');

            $insert['sent_from_office_code'] = $sent_from_office_code;
            $insert['sent_to_office'] = NULL;
            $insert['sent_to_office_code'] = NULL;
            $insert['complaint_status_id'] = $request->complaint_status_id;
        } else {
            \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' requested to close.');

            $insert['sent_from_office_code'] = $sent_from_office_code;
            $insert['sent_to_office'] = $labour_office->id;
            $insert['sent_to_office_code'] = $sent_to_office_code;
            $insert['complaint_status_id'] = 0;
        }

        if($request->remark_option5 != null){
            if($request->remark_option5 == 'Other'){
                $remark = $request->remark;
            } else {
                $remark = $request->remark_option5;
            }
        } else {
            $remark = "";
        }

        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = $status;
        $insert['action_type'] = $action_type;
        $insert['remark'] = $remark;
        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['forward_type_id'] = 0;
        $insert['status_des'] = $status_des;

        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        if($action_type == 'Tempclosed') {
            \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status Closed.');
        } else {
            \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with status Request_approve_close.');
        }


        if($complaint->complainant_email != '' && $action_type == 'Closed'){

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

                $complainantname = $complaint->complainant_f_name;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$title,$complaintdetails->current_employer_name,$complaintdetails->employer_address,$complaintdetails->employer_name,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address,$officename->office_name_en,$remark,$complaintdetails->complainant_f_name,$complaintdetails->complainant_address];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_en);

                // $email_body = 'Dear'.' '.$complainantname.','.$e_body;

            } else if($complaint->pref_lang == 'SI'){
                $e_sub = $mailitem[0]->mail_template_name_sin;
                // $e_body = $mailitem[0]->body_content_sin;
                $e_name = $mailitem[0]->mail_template_name_sin;

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

                $complainantname = $complaint->complainant_f_name_si;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$title,$complaintdetails->current_employer_name_si,$complaintdetails->employer_address_si,$complaintdetails->employer_name_si,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_si,$officename->office_name_si,$remark,$complaintdetails->complainant_f_name_si,$complaintdetails->complainant_address_si];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_sin);

                // $email_body = 'Dear'.' '.$complainantname.','.$e_body;

            } else if($complaint->pref_lang == 'TA'){
                $e_sub = $mailitem[0]->mail_template_name_tam;
                // $e_body = $mailitem[0]->body_content_tam;
                $e_name = $mailitem[0]->mail_template_name_tam;

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

                $complainantname = $complaint->complainant_f_name_ta;

                $variables = ['[TITLE]','[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[REMARK]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$title,$complaintdetails->current_employer_name_ta,$complaintdetails->employer_address_ta,$complaintdetails->employer_name_ta,$complaintdetails->external_ref_no,$complaintdetails->current_employer_address_ta,$officename->office_name_ta,$remark,$complaintdetails->complainant_f_name_ta,$complaintdetails->complainant_address_ta];

                $e_body = str_ireplace($variables, $variableData, $mailitem[0]->body_content_tam);

                // $email_body = 'Dear'.' '.$complainantname.','.$e_body;
            }

            \Mail::send('mail.complaint-mail',
                array(
                'ref_no' => $complaint->external_ref_no,
                'date' => $complaint->created_at,
                    'name' => $complainantname,
                    'subject' => $e_sub,
                    'body' => $e_body,
                ), function($message) use ($e_name, $complaint)
            {
                $message->from('cms@labourdept.gov.lk');
                $message->to($complaint->complainant_email)->subject($e_name);
            });

            \EmailLog::addToLog($complaint->complainant_f_name, $complaint->complainant_email, $e_sub, $e_body);
        }

        if($complaint->complainant_mobile != '' && $action_type == 'Closed'){

            $smsitem = SmsTemplate::where('status', 'Y')
                ->where('is_delete', 0)
                ->where('id', 2)
                ->get();

            // dd($smsitem);

            if($complaint->pref_lang == 'EN'){
                $s_sub = $smsitem[0]->sms_template_name_en;
                // $s_body = $smsitem[0]->body_content_en;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[REMARK]','[COMPLAINANTNAME]'];

                $variableData = [$officename->office_name_en,$complaint->external_ref_no,$remark,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_en);

                $sms_body = $s_body;

            } else if($complaint->pref_lang == 'SI'){
                $s_sub = $smsitem[0]->sms_template_name_sin;
                // $s_body = $smsitem[0]->body_content_sin;

                $variables = ['[OFFICENAME]','REFERENCENUMBER','[REMARK]','[COMPLAINANTNAME]'];

                $variableData = [$officename->office_name_sin,$complaint->external_ref_no,$remark,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_sin);

                $sms_body = $s_body;

            } else if($complaint->pref_lang == 'TA'){
                $s_sub = $smsitem[0]->sms_template_name_tam;
                // $s_body = $smsitem[0]->body_content_tam;

                $variables = ['[OFFICENAME]','[REFERENCENUMBER]','[REMARK]','[COMPLAINANTNAME]'];

                $variableData = [$officename->office_name_sin,$complaint->external_ref_no,$remark,$complaint->complainant_f_name.' '.$complaint->complainant_l_name];

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

            \SmsLog::addToLog($complaint->complainant_f_name.' '.$complaint->complainant_l_name, $complaint->complainant_mobile, $sms_body);
        }

        DB::commit();
        return redirect()->route('action-pending-list')
            ->with('success', 'Complaint closed.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function statusUpdate(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            // 'remark_option6' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }
        $complaint->current_office_id = Auth::user()->office_id;
        //$complaint->complaint_status = 'Update';
        if($complaint->action_type != 'Pending_recovery' && $complaint->action_type != 'Waiting'){
            $complaint->action_type = 'Ongoing';
        } else if($complaint->action_type == 'Waiting' && $request->complaint_status_id == 58) {
            $complainthis = ComplaintHistory::where('complaint_id', $request->complaint_id)->where('status_to_revert', '!=', null)->orderBy('created_at', 'desc')->first();
            $revert = explode(',', $complainthis->status_to_revert);
            $complaint->action_type = $revert[0];
            $complaint->complaint_status = $revert[1];
            $complaint->current_office_id = $complainthis->sent_from_office;
        }
        $complaint->save();

        $status = ComplaintStatus::where('id', $request->complaint_status_id)->first();

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' status Update.');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Update';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = NULL;
        $insert['sent_to_office_code'] = NULL;
        if($complaint->action_type != 'Waiting'){
            $insert['action_type'] = 'Ongoing';
        }
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
        return redirect()->route('action-pending-list')
            ->with('success', 'Complaint status updated successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function assignlo(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'labour_officer_id' => 'required'
        ]);

        $complaint =  RegisterComplaint::find($request->complaint_id);

        $labour_office = LabourOfficeDivision::where('id', Auth::user()->office_id)->first();

        if (!empty($labour_office)) {
            $sent_from_office_code = $labour_office->office_code;
        } else {
            $sent_from_office_code = NULL;
        }

        $complaint->complaint_status = 'Request_assign_lo';
        $complaint->action_type = 'Pending_approve';
        $complaint->current_office_id = Auth::user()->office_id;
        // $complaint->lo_officer_id = $request->labour_officer_id;
        $complaint->save();

        \LogActivity::addToLog('Complaint number '.$complaint->external_ref_no.' status LOAllocateD');

        $insert['complaint_id'] = $request->complaint_id;
        $insert['status'] = 'Request_assign_lo';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = Auth::user()->office_id;
        $insert['sent_to_office_code'] = $sent_from_office_code;
        $insert['action_type'] = 'Pending_approve';
        if($request->remark_option3 == 'Other'){
            $insert['remark'] = $request->remark;
        } else {
            $insert['remark'] = $request->remark_option3;
        }
        $insert['show_status'] = 'Ext';
        $insert['user_id'] = Auth::user()->id;
        $insert['assigned_lo_id'] = $request->labour_officer_id;
        $insert['forward_type_id'] = 10;
        $insert['complaint_status_id'] = 0;

        if($complaint->pref_lang == "SI") {
            $insert['status_des'] = 'කම්කරු නිලධාරීවරයා පත්කිරීම සඳහා අනුමැතිය බලාපොරොත්තුවෙනි';
        } else if($complaint->pref_lang == "TA") {
            $insert['status_des'] = 'தொழிலாளர் அலுவலரை நியமிப்பதற்கான ஒப்புதலுக்காக காத்திருப்பு';
        } else {
            $insert['status_des'] = 'Approval waiting to assign labour officer';
        }

        ComplaintHistory::insert($insert);
        $id = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$id.') added to complaint number '.$complaint->external_ref_no.' with Request_assign_lo.');

        DB::commit();
        return redirect()->route('action-pending-list')
            ->with('success', 'Complaint forward successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    public function printcomplaint($id) {

        // $PrintId = $request->printId;
        // $complaintId = $request->refid;
        $data = RegisterComplaint::find($id);

        $complaintdocuments = ComplaintDocument::where('ref_no', $id)->get();

        $complainthistorydetails = ComplaintHistory::where('complaint_id', $id)->first();

        $complaintcategorydetails = ComplaintCategoryDetail::with('complaintcategories')->where('complaint_id', $id)->get();

        // dd($id);

        return view('adminpanel.complaint.print_complaint', compact('data','complaintdocuments','complainthistorydetails','complaintcategorydetails'));
    }

    public function createcomplaintcopy(Request $request)
    {
        try {
            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

        $request->validate([
            'labour_office_id' => 'required',
            'category_id' => 'required'
        ]);

        $categories = Complain_Category::where('id', $request->input('category_id'))->first();

        $cur_complaint =  RegisterComplaint::find($request->complaint_id);

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

        $category_prefix = "";
        if ($request->category_id != '') {
            if (count($request->category_id) > 1) {
                $category_prefix = "M";
            } else {
                $categoryInfo = Complain_Category::where('id', $request->category_id[0])->first();

                if (!empty($categoryInfo)) {
                    $category_prefix = $categoryInfo->category_prefix;
                } else {
                    $category_prefix = NULL;
                }
            }
            $category_id = implode(',', $request->input('category_id'));
        }

        $result = RegisterComplaint::select('id', 'created_at', 'external_ref_no')
            ->orderby('id', 'desc')
            ->first();

            if (!empty($result)) {
                // dd($result->external_ref_no);
                $refNo = $result->external_ref_no;
                $arrSerial = explode('/', $refNo);
                if ($arrSerial) {
                    $oldYear = $arrSerial[1];
                    $oldRef = $arrSerial[2];
                }
            } else {
                $oldYear = Carbon::now()->format('Y');
                $oldRef = 0;
            }

        $newYear = Carbon::now()->format('Y');

        if ($oldYear != $newYear) {
            $newRef = 0;

            $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

            $number_part1 = 'COM';
            $new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

            $checkDuplicate = RegisterComplaint::where('external_ref_no', $new_complaint_no)->count();

            if($checkDuplicate > 0) {

                $maxrec = RegisterComplaint::max('external_ref_no');

                $splitref = explode('/', $maxrec);

                $refno = $splitref[2];

                $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

                $new_complaint_no = $number_part1 . '/' . date('Y'). '/' . $newRef;
            }

        } else {
            $newRef = str_pad((int) $oldRef + 1, 5, '0', STR_PAD_LEFT);
            $number_part1 = 'COM';
            $new_complaint_no = $number_part1 . '/' . date('Y') . '/' . $newRef;

            $checkDuplicate = RegisterComplaint::where('external_ref_no', $new_complaint_no)->count();

            if($checkDuplicate > 0) {

                $maxrec = RegisterComplaint::max('external_ref_no');

                $splitref = explode('/', $maxrec);

                $refno = $splitref[2];

                $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

                $new_complaint_no = $number_part1 . '/' . date('Y'). '/' . $newRef;
            }
        }

        $result2 = RegisterComplaint::select('id', 'created_at', 'ref_no')
            ->where('ref_no', 'LIKE', '%'.$sent_to_office_code.'%')
            ->orderby('id', 'desc')
            ->first();

        if (!empty($result2)) {
            //dd($result2->ref_no);
            $refNo2 = $result2->ref_no;
            $arrSerial2 = explode('/', $refNo2);
            if ($arrSerial2) {
                $oldYear2 = substr($arrSerial2[4], 0, 4);
                $oldRef2 = $arrSerial2[5];
            }
        } else {
            $oldRef2 = 0;
        }

        $number_part3 = "00";

        if ($oldYear2 != $newYear) {
            $newRef = 0;

            $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

            $number_part1 = 'COM';

            $internal_number = $sent_to_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;

            $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $sent_to_office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();


            if($checkDuplicate > 0) {
                $this->makeNewRef($sent_to_office_code,$newRef);

                $newRef = $this->makeNewRef($sent_to_office_code, $newRef);

                if($newRef != '') {
                    $internal_number = $sent_to_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                }
            }

            // $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $sent_to_office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

            // if($checkDuplicate > 0) {

            //     $maxrec = RegisterComplaint::where('ref_no','LIKE','%'.$sent_to_office_code.'%')->max('ref_no');

            //     $splitref = explode('/', $maxrec);

            //     $refno = $splitref[5];

            //     $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

            //     $internal_number = $sent_to_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;
            // }

        } else {
            $newRef = str_pad((int) $oldRef2 + 1, 5, '0', STR_PAD_LEFT);

            $number_part1 = 'COM';
            $internal_number = $sent_to_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

            $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $sent_to_office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();


            if($checkDuplicate > 0) {
                $this->makeNewRef($sent_to_office_code,$newRef);

                $newRef = $this->makeNewRef($sent_to_office_code, $newRef);

                if($newRef != '') {
                    $internal_number = $sent_to_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' .  $newRef;

                }
            }

            // $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $sent_to_office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

            // if($checkDuplicate > 0) {

            //     $maxrec = RegisterComplaint::where('ref_no','LIKE','%'.$sent_to_office_code.'%')->max('ref_no');

            //     $splitref = explode('/', $maxrec);

            //     $refno = $splitref[5];

            //     $newRef = str_pad($refno + 1, 5, '0', STR_PAD_LEFT);

            //     $internal_number = $sent_to_office_code . '/' . $number_part1 . '/' . $number_part3 . '/' . $category_prefix . '/' . date('Y'). date('m'). '/' . $newRef;
            // }

        }

        $complaint = new RegisterComplaint;

        $complaint->comp_type = $cur_complaint->comp_type;
        $complaint->pref_lang = $cur_complaint->pref_lang;
        // $complaint->ref_no = $cur_complaint->ref_no;
        // $complaint->external_ref_no = $cur_complaint->external_ref_no;
        $complaint->complainant_identify_no = $cur_complaint->complainant_identify_no;
        $complaint->title = $cur_complaint->title;
        $complaint->complainant_f_name = $cur_complaint->complainant_f_name;
        $complaint->complainant_l_name = $cur_complaint->complainant_l_name;
        $complaint->complainant_full_name = $cur_complaint->complainant_full_name;
        $complaint->complainant_dob = $cur_complaint->complainant_dob;
        $complaint->complainant_gender = $cur_complaint->complainant_gender;
        $complaint->nationality = $cur_complaint->nationality;
        $complaint->complainant_email = $cur_complaint->complainant_email;
        $complaint->complainant_mobile = $cur_complaint->complainant_mobile;
        $complaint->complainant_tel = $cur_complaint->complainant_tel;
        $complaint->complainant_address = $cur_complaint->complainant_address;
        $complaint->union_name = $cur_complaint->union_name;
        $complaint->union_address = $cur_complaint->union_address;
        $complaint->employer_name = $cur_complaint->employer_name;
        $complaint->employer_address = $cur_complaint->employer_address;
        $complaint->province_id = $cur_complaint->province_id;
        $complaint->district_id = $cur_complaint->district_id;
        $complaint->city_id = $cur_complaint->city_id;
        $complaint->employer_tel = $cur_complaint->employer_tel;
        $complaint->business_nature_id = $cur_complaint->business_nature_id;
        $complaint->establishment_type_id = $cur_complaint->establishment_type_id;
        $complaint->establishment_reg_no = $cur_complaint->establishment_reg_no;
        $complaint->employer_no = $cur_complaint->employer_no;
        // $complaint->ppe_no = $cur_complaint->ppe_no;
        $complaint->employee_no = $cur_complaint->employee_no;
        $complaint->epf_no = $cur_complaint->epf_no;
        $complaint->employee_mem_no = $cur_complaint->employee_mem_no;
        $complaint->designation = $cur_complaint->designation;
        $complaint->join_date = $cur_complaint->join_date;
        $complaint->terminate_date = $cur_complaint->terminate_date;
        $complaint->last_sal_date = $cur_complaint->last_sal_date;
        $complaint->basic_sal = $cur_complaint->basic_sal;
        $complaint->allowance = $cur_complaint->allowance;
        $complaint->submitted_office = $cur_complaint->submitted_office;
        $complaint->submitted_date = $cur_complaint->submitted_date;
        $complaint->case_no = $cur_complaint->case_no;
        $complaint->received_relief = $cur_complaint->received_relief;
        $complaint->complain_purpose = $cur_complaint->complain_purpose;
        $complaint->is_available = $cur_complaint->is_available;
        $complaint->complain_category = $category_id;
        $complaint->external_ref_no = $new_complaint_no;
        $complaint->ref_no = $internal_number;
        $complaint->complaint_status = 'New';
        $complaint->action_type = 'Pending';
        $complaint->current_office_id = $request->labour_office_id;
        $complaint->current_employer_name = $cur_complaint->current_employer_name;
        $complaint->current_employer_address = $cur_complaint->current_employer_address;
        $complaint->current_employer_tel = $cur_complaint->current_employer_tel;
        $complaint->copied_ref_no = $cur_complaint->external_ref_no;
        $complaint->save();
        $id = $complaint->id;

        \LogActivity::addToLog('New Complaint added. Complaint number '.$cur_complaint->external_ref_no.'. It is copy of complaint number '.$cur_complaint->external_ref_no.'.');

        $cats = $request->category_id;

        $complaintcat = ComplaintCategoryDetail::select('id')
            ->where('complaint_id', $request->complaint_id)
            ->Where(function ($query) use($cats) {
                for ($i = 0; $i < count($cats); $i++){
                   $query->orwhere('category_id', '=',$cats[$i]);
                }
           })->get();

        foreach($complaintcat as $item) {
            $complaintcat =  ComplaintCategoryDetail::find($item->id);
            $complaintcat->complaint_id = $id;
            $complaintcat->save();

            \LogActivity::addToLog('Complaint category detail record('.$complaintcat->id.') complaint number '.$item->external_ref_no.' changed to '.$complaint->external_ref_no.'.');
        }

        $complaintcopy['copied_user'] = Auth::user()->id;
        $complaintcopy['copied_office'] =  Auth::user()->office_id;
        $complaintcopy['parent_complaint_id'] = $request->complaint_id;
        $complaintcopy['current_complaint_id'] = $id;
        RegisterComplaintCopy::insert($complaintcopy);
        $regid = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('New register complaint copy added('.$regid.'). Parent complaint of complaint number '.$complaint->external_ref_no.' is '.$cur_complaint->external_ref_no.'.');

        $insert['complaint_id'] = $id;
        $insert['status'] = 'New';
        $insert['sent_from_office'] = Auth::user()->office_id;
        $insert['sent_from_office_code'] = $sent_from_office_code;
        $insert['sent_to_office'] = $request->labour_office_id;
        $insert['sent_to_office_code'] = $sent_to_office_code;
        $insert['action_type'] = 'New';
        if($request->remark_option2 == 'Other'){
            $insert['remark'] = $request->remark;
        } else {
            $insert['remark'] = $request->remark_option2;
        }
        $insert['show_status'] = 'Ext';
        $insert['assigned_lo_id'] = NULL;
        $insert['forward_type_id'] = 0;
        $insert['user_id'] = Auth::user()->id;
        $insert['complaint_status_id'] = 0;

        if($cur_complaint->pref_lang == "SI") {
            $insert['status_des'] = "පිටපත් කරන ලද පැමිණිල්ල සාර්ථකව ලැබුණා. පැමිණිලි ක්‍රියාවලිය ළඟදීම ආරම්භ වේ";
        } else if($cur_complaint->pref_lang == "TA") {
            $insert['status_des'] = "நகலெடுக்கப்பட்ட புகார் வெற்றிகரமாகப் பெறப்பட்டது. புகார் நடவடிக்கை விரைவில் தொடங்கும்";
        }  else {
            $insert['status_des'] = "Copied Complaint received successfully. Complaint process will start soon";
        }

        ComplaintHistory::insert($insert);
        $comid = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$comid.') added to complaint number '.$complaint->external_ref_no.' with status New.');

        $insert_copy['complaint_id'] = $request->complaint_id;
        $insert_copy['status'] = 'New';
        $insert_copy['sent_from_office'] = Auth::user()->office_id;
        $insert_copy['sent_from_office_code'] = $sent_from_office_code;
        $insert_copy['sent_to_office'] = $request->labour_office_id;
        $insert_copy['sent_to_office_code'] = $sent_to_office_code;
        $insert_copy['action_type'] = 'Copied';
        if($request->remark_option2 == 'Other'){
            $insert_copy['remark'] = $request->remark;
        } else {
            $insert_copy['remark'] = $request->remark_option2;
        }
        $insert_copy['status_des'] = "$cur_complaint->ref_no Copied and Comptaint category $categories->category_name_en forward to $sent_to_office_code office";
        $insert_copy['show_status'] = 'Ext';
        $insert_copy['forward_type_id'] = 0;
        $insert_copy['user_id'] = Auth::user()->id;
        $insert_copy['complaint_status_id'] = 0;
        ComplaintHistory::insert($insert_copy);
        $comid = \DB::getPdo()->lastInsertId();

        \LogActivity::addToLog('History record('.$comid.') added to complaint number '.$complaint->external_ref_no.' with status New.');

        $regdata = RegisterComplaint::where('id', $complaint->id)
                                        ->first();

        $officename = LabourOfficeDivision::where('id', $request->labour_office_id)->first();

        if($cur_complaint->complainant_email != ''){

            $mailitem = MailTemplate::where('status', 'Y')
                    ->where('is_delete', 0)
                    ->where('id', 15)
                    ->first();
            //dd();exit();
            //\App::setLocale($regdata[0]->pref_lang);

            if($regdata->pref_lang == 'EN'){
                $e_sub = $mailitem->mail_template_name_en;
                // $e_body = $mailitem->body_content_en;
                $e_name = $mailitem->mail_template_name_en;

                $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$cur_complaint->current_employer_name,$cur_complaint->employer_address,$cur_complaint->employer_name,$new_complaint_no,$cur_complaint->current_employer_address,$officename->office_name_en,$cur_complaint->complainant_f_name,$cur_complaint->complainant_address];

                $e_body = str_ireplace($variables, $variableData, $mailitem->body_content_en);

                $email_body = 'Dear'.' '.$cur_complaint->complainant_f_name.', '.$e_body;

            } else if($regdata->pref_lang == 'SI'){
                $e_sub = $mailitem->mail_template_name_sin;
                // $e_body = $mailitem->body_content_sin;
                $e_name = $mailitem->mail_template_name_sin;

                $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$cur_complaint->current_employer_name,$cur_complaint->employer_address,$cur_complaint->employer_name,$new_complaint_no,$cur_complaint->current_employer_address,$officename->office_name_sin,$cur_complaint->complainant_f_name,$cur_complaint->complainant_address];

                $e_body = str_ireplace($variables, $variableData, $mailitem->body_content_sin);

                $email_body = 'Dear'.' '.$cur_complaint->complainant_f_name.', '.$e_body;

            } else if($regdata->pref_lang == 'TA'){
                $e_sub = $mailitem->mail_template_name_tam;
                // $e_body = $mailitem->body_content_tam;
                $e_name = $mailitem->mail_template_name_tam;

                $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$cur_complaint->current_employer_name,$cur_complaint->employer_address,$cur_complaint->employer_name,$new_complaint_no,$cur_complaint->current_employer_address,$officename->office_name_tam,$cur_complaint->complainant_f_name,$cur_complaint->complainant_address];

                $e_body = str_ireplace($variables, $variableData, $mailitem->body_content_tam);

                $email_body = 'Dear'.' '.$cur_complaint->complainant_f_name.', '.$e_body;
            }

            // $email_body = 'Dear'.' '.$cur_complaint->complainant_f_name.', '.$e_body.' - '.$new_complaint_no;


            \Mail::send('mail.complaint-mail',
                array(
                'ref_no' => $regdata->external_ref_no,
                'date' => $regdata->created_at,
                    'name' => $cur_complaint->complainant_f_name,
                    'subject' => $e_sub,
                    'body' => $email_body,
                ), function($message) use ($e_name, $regdata)
            {
                $message->from('cms@labourdept.gov.lk');
                $message->to($regdata->complainant_email)->subject($e_name);
            });

            \EmailLog::addToLog($cur_complaint->complainant_f_name, $regdata->complainant_email, $e_sub, $email_body);
        }

        if($cur_complaint->complainant_mobile != ''){

            $smsitem = SmsTemplate::where('status', 'Y')
                ->where('is_delete', 0)
                ->where('id', 7)
                ->first();

            $complainant_f_name = $cur_complaint->complainant_f_name.' '.$cur_complaint->complainant_l_name;

            $catlist = $request->category_id;

            $TLMessageEn = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
            $TLMessageSi = "Please lodge a complaint with the relevant Labor Tribunal within six months of the last working day if your complaint is for re-employment or compensation for termination of service.";
            $TLMessageTa = "உங்களது புகார் மீண்டும் பணியமர்த்தல் அல்லது சேவையை நிறுத்துவது தொடர்பாக இழப்பீடு வழங்குவதாக இருந்தால், கடைசி வேலை நாளிலிருந்து 06 மாதங்களுக்குள் சம்பந்தப்பட்ட தொழிலாளர் தீர்ப்பாயத்தில் புகார் அளிக்குமாறும் உங்களுக்குத் தெரிவிக்கிறேன்.";

            if($regdata->pref_lang == 'EN'){
                $s_sub = $smsitem->sms_template_name_en;
                // $s_body = $smsitem->body_content_en;

                $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$cur_complaint->current_employer_name,$cur_complaint->employer_address,$cur_complaint->employer_name,$new_complaint_no,$cur_complaint->current_employer_address,$officename->office_name_en,$cur_complaint->complainant_f_name.' '.$cur_complaint->complainant_l_name,$cur_complaint->complainant_address];

                $s_body = str_ireplace($variables, $variableData, $smsitem->body_content_en);

                if(in_array("4", $catlist)) {

                    $sms_body = 'Dear '.$complainant_f_name.', '.$s_body.' '.$TLMessageEn;

                } else {

                    $sms_body = 'Dear '.$complainant_f_name.', '.$s_body;

                }

            } else if($regdata->pref_lang == 'SI'){
                $s_sub = $smsitem->sms_template_name_sin;
                // $s_body = $smsitem->body_content_sin;

                $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$cur_complaint->current_employer_name,$cur_complaint->employer_address,$cur_complaint->employer_name,$new_complaint_no,$cur_complaint->current_employer_address,$officename->office_name_sin,$cur_complaint->complainant_f_name.' '.$cur_complaint->complainant_l_name,$cur_complaint->complainant_address];

                $s_body = str_ireplace($variables, $variableData, $smsitem->body_content_sin);

                if(in_array("4", $catlist)) {

                    $sms_body = 'Dear '.$complainant_f_name.', '.$s_body.' '.$TLMessageSi;

                } else {

                    $sms_body = 'Dear '.$complainant_f_name.', '.$s_body;

                }

            } else if($regdata->pref_lang == 'TA'){
                $s_sub = $smsitem->sms_template_name_tam;
                // $s_body = $smsitem->body_content_tam;

                $variables = ['[COMPANYNAME]','[EMPADDRESS]','[EMPNAME]','[REFERENCENUMBER]','[COMPANYADDRESS]','[OFFICENAME]','[COMPLAINANTNAME]','[COMPLAINANTADDRESS]'];

                $variableData = [$cur_complaint->current_employer_name,$cur_complaint->employer_address,$cur_complaint->employer_name,$new_complaint_no,$cur_complaint->current_employer_address,$officename->office_name_tam,$cur_complaint->complainant_f_name.' '.$cur_complaint->complainant_l_name,$cur_complaint->complainant_address];

                $s_body = str_ireplace($variables, $variableData, $smsitem[0]->body_content_tam);

                if(in_array("4", $catlist)) {

                    $sms_body = 'Dear '.$complainant_f_name.', '.$s_body.' '.$TLMessageTa;

                } else {

                    $sms_body = 'Dear '.$complainant_f_name.', '.$s_body;

                }
            }

            $mobitelSms = new MobitelSms();
            $session = $mobitelSms->createSession('','esmsusr_uqt','2L@boUr$m$','');
            $mobitelSms->sendMessagesMultiLang($session,'Labour Dept',$sms_body,array($cur_complaint->complainant_mobile),0);
            $mobitelSms->closeSession($session);

            \SmsLog::addToLog($cur_complaint->complainant_f_name.' '.$cur_complaint->complainant_l_name, $cur_complaint->complainant_mobile, $sms_body);
        }

        DB::commit();
        return redirect()->route('action-pending-list')
            ->with('success', 'Complaint forward successfully.');

        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }

    }

    function makeNewRef($office_code,$newRef) {

        $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

        $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

        while($checkDuplicate > 0) {
            $newRef = str_pad($newRef + 1, 5, '0', STR_PAD_LEFT);

            $checkDuplicate = RegisterComplaint::where('ref_no', 'LIKE', '%'. $office_code .'%')->where('ref_no', 'LIKE', '%'. $newRef .'%')->count();

        }

        return $newRef;

    }

    public function pendingAppealList(Request $request)
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
                ->where('action_type', '=', 'Waiting')
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
                ->rawColumns(['status', 'action', 'created_at', 'upload', 'modify', 'view', 'calculation', 'online_manual'])
                ->make(true);
        }

        return view('adminpanel.complaint.appealList', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount, 'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

    public function sentApprovalList(Request $request)
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

        $approveCount = RegisterComplaint::where('complaint_status','Approve')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Reject')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingApprovalCount = RegisterComplaint::where('action_type','Pending_approve')
                            ->where('current_office_id',$office_id)
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

        if ($request->ajax()) {
            $data = RegisterComplaint::select('id','ref_no','external_ref_no','complainant_f_name','complainant_full_name','created_at','complainant_identify_no','complaint_status','updated_at')->where('action_type','Pending_approve')->where('current_office_id',$office_id);
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
                //->addColumn('status', 'adminpanel.complaint.pendingApprovalStatus', ['statusCount' => $statusCount])
                //->addColumn('action', 'adminpanel.complaint.pendingApprovalAction')
                ->addColumn('view', function ($row) {
                    $view_url = url('/pending-approval-view/' . encrypt($row->id) . '');
                    $btn = '<a href="' . $view_url . '" target="_blank" title="view" > <i class="fa fa-file-text"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.sent_approval', ['pendingApprovalCount' => $pendingApprovalCount, 'rejectCount' => $rejectCount, 'approveCount' => $approveCount, 'pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }

    public function wcaComplaintList(Request $request)
    {
        $office_id = Auth::user()->office_id;


        $approveCount = RegisterComplaint::where('complaint_status','Approve')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Reject')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingApprovalCount = RegisterComplaint::where('action_type','Pending_approve')
                            ->where('current_office_id',$office_id)
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

            // $data[] = array(
            //     "id" => $item->id,
            //     "office" => $item->office_name_en,
            //     'maternity_ben_leave' => $maternityBenLeave[$key],
            //     'child_labour' => $childLabour[$key],
            //     'female_emp_night' => $femaleEmpNight[$key]
            // );
        }

        $totalWcaComplaint = $count;

        if ($request->ajax()) {

            $labouroffice = LabourOfficeDivision::where('status', 'Y')
            ->where('is_delete', '0')
            ->orderBy('office_name_en', 'ASC')
            ->get();

            // dd($labouroffice);

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

                if(Session::get('applocale') == 'ta'){
                    $office_name = $item->office_name_tam;
                } else if (Session::get('applocale') == 'si'){
                    $office_name = $item->office_name_sin;
                } else {
                    $office_name = $item->office_name_en;
                }

                $count += $maternityBenLeave[$key] + $childLabour[$key] + $femaleEmpNight[$key];

                $data[] = array(
                    "id" => $item->id,
                    "office" => $office_name,
                    'maternity_ben_leave' => $maternityBenLeave[$key],
                    'child_labour' => $childLabour[$key],
                    'female_emp_night' => $femaleEmpNight[$key]
                );

            }

            return Datatables::of($data)
            ->addIndexColumn()
            // ->addColumn('status', 'adminpanel.complaint.pendingApprovalStatus', ['statusCount' => $statusCount])
            // ->addColumn('action', 'adminpanel.complaint.pendingApprovalAction')
            ->addColumn('view', function ($row) {
                // dd($row['id']);
                $view_url = url('/wca-complaint-officewise-list/'.encrypt($row['id']) . '');
                $btn = '<a href="' . $view_url .'"target="_blank" title="view" > <i class="fa fa-list"></i> </a>';
                return $btn;
            })
            ->rawColumns(['view'])
            ->make(true);
        }

        // $totalWcaComplaint = $count;

        return view('adminpanel.complaint.wca_complaint_list', ['pendingApprovalCount' => $pendingApprovalCount, 'rejectCount' => $rejectCount, 'approveCount' => $approveCount, 'pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'totalWcaComplaint' => $totalWcaComplaint ]);
    }

    public function wcaComplaintOfficeWiseList(Request $request, $id)
    {
        $office_id = Auth::user()->office_id;

        $wcaCompOfficeID = decrypt($id);

        $approveCount = RegisterComplaint::where('complaint_status','Approve')
                            ->where('current_office_id',$office_id)
                            ->count();
        $rejectCount = RegisterComplaint::where('complaint_status','Reject')
                            ->where('current_office_id',$office_id)
                            ->count();
        $pendingApprovalCount = RegisterComplaint::where('action_type','Pending_approve')
                            ->where('current_office_id',$office_id)
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

        $labouroffice = LabourOfficeDivision::where('id', $wcaCompOfficeID)->first();

        if(Session::get('applocale') == 'ta'){
            $office_name = $labouroffice->office_name_tam;
        } else if (Session::get('applocale') == 'si'){
            $office_name = $labouroffice->office_name_sin;
        } else {
            $office_name = $labouroffice->office_name_en;
        }

        if ($request->ajax()) {
            $data = RegisterComplaint::select('register_complaints.id','register_complaints.ref_no','register_complaints.external_ref_no','register_complaints.complainant_f_name','register_complaints.complainant_full_name','register_complaints.complainant_mobile','register_complaints.created_at','register_complaints.complainant_identify_no','register_complaints.complaint_status','register_complaints.updated_at')
                    ->join('complaint_category_details', 'register_complaints.id', '=', 'complaint_category_details.complaint_id')
                    ->whereIn('complaint_category_details.category_id', [8,16,7])
                    ->where('register_complaints.complaint_status', '<>', 'closed')
                    ->where('current_office_id',$wcaCompOfficeID);
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
                    $status_url = url('/pending-approval-status-history/' . encrypt($row->id) . '');
                    $btn = '<a  href="'.$status_url.'" title="Frontoffice Remarks"><i class="fa fa-comments "></i></a> '.$statusCount;
                    return $btn;
                })
                //->addColumn('status', 'adminpanel.complaint.pendingApprovalStatus', ['statusCount' => $statusCount])
                //->addColumn('action', 'adminpanel.complaint.pendingApprovalAction')
                ->addColumn('view', function ($row) {
                    $view_url = url('/pending-approval-view/' . encrypt($row->id) . '');
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
                ->rawColumns(['status', 'view'])
                ->make(true);
        }

        return view('adminpanel.complaint.wca_complaint_officewise_list', ['pendingApprovalCount' => $pendingApprovalCount, 'rejectCount' => $rejectCount, 'approveCount' => $approveCount, 'pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'office_name' => $office_name ]);
    }

    public function assignComplaintList(Request $request)
    {
        $userrole = Auth::user()->roles->pluck('name')->first();

        $office_id = Auth::user()->office_id;

        $user_id = Auth::user()->id;

        // $loOfficerWiseComplaints = RegisterComplaint::where('lo_officer_id', $user_id)->count();

        $assignCount = RegisterComplaint::where('register_complaints.lo_officer_id', $user_id)
            ->where('register_complaints.current_office_id', $office_id)
            ->where('register_complaints.action_type', '<>', 'Closed')
            ->where('register_complaints.action_type', '<>', 'Pending_approve')
            ->where('register_complaints.action_type', '<>', 'Pending_legal')
            ->where('register_complaints.action_type', '<>', 'Tempclosed')
            ->count();


            // ->select('register_complaints.*');

        // $assignCount = ComplaintHistory::where('user_id', $user_id)->groupBy('complaint_id')->count();

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
            $data = RegisterComplaint::where('register_complaints.lo_officer_id', $user_id)
                ->where('register_complaints.current_office_id', $office_id)
                ->where('register_complaints.action_type', '<>', 'Closed')
                ->where('register_complaints.action_type', '<>', 'Pending_approve')
                ->where('register_complaints.action_type', '<>', 'Pending_legal')
                ->where('register_complaints.action_type', '<>', 'Tempclosed');


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
                ->rawColumns(['status', 'action', 'created_at', 'upload', 'modify', 'view', 'calculation', 'online_manual'])
                ->make(true);

        }

        return view('adminpanel.complaint.assignComplaintList', ['pendingCount' => $pendingCount, 'ongoingCount' => $ongoingCount, 'tempClosedCount' => $tempClosedCount, 'closedCount' => $closedCount, 'certificateCount' => $certificateCount, 'chargesheetCount' => $chargesheetCount, 'recoveryCount' => $recoveryCount, 'appealCount' => $appealCount, 'pendingApprovalCount' => $pendingApprovalCount, 'office_id' => $office_id, 'totalWcaComplaint' => $totalWcaComplaint, 'assignCount' => $assignCount, 'userrole' => $userrole]);
    }
}

